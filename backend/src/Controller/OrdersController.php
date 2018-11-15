<?php

namespace App\Controller;

use App\Entity\Coffee;
use App\Entity\Order;
use App\Entity\OrderedCoffee;
use App\Entity\User;
use App\Model\Coffee\OrderDto;
use App\Service\OrderService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Security\Core\User\UserInterface;


class OrdersController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var OrderService */
    private $orderService;

    public function __construct(EntityManagerInterface $em,
                                OrderService $orderService)
    {
        $this->em = $em;
        $this->orderService = $orderService;
    }

    /**
     * Crée une nouvelle commande.
     *
     * @View()
     * @Post("/order")
     *
     * @ParamConverter("data", converter="fos_rest.request_body")
     *
     * @param Request $request
     * @param UserInterface $user
     * @param OrderDto $data
     * @return Order
     */
    public function orderAction(Request $request, UserInterface $user, OrderDto $data)
    {
        $userRepo = $this->em->getRepository(User::class);
        $coffeeRepo = $this->em->getRepository(Coffee::class);
        $order = new Order();
        $order->setUser($userRepo->findOneByUsername($user->getUsername()));
        $order->setItems(new ArrayCollection());

        foreach ($data->getItems() as $item) {
            $orderedCoffee = new OrderedCoffee();
            $orderedCoffee->setOrder($order);
            $orderedCoffee->setCoffee($coffeeRepo->find($item->getId()));
            $orderedCoffee->setQuantity30($item->getQuantity30());
            $orderedCoffee->setQuantity50($item->getQuantity50());
            $order->getItems()->add($orderedCoffee);
        }

        $this->em->merge($order);
        $this->em->flush();
        return $order;
    }

    /**
     * Récupère toutes les commandes en attente de l'utilisateur courant.
     *
     * @View()
     * @Get("/orders/waiting")
     *
     * @param Request $request
     * @param UserInterface $user
     * @return OrderDto[]
     */
    public function waitingOrdersAction(Request $request, UserInterface $user)
    {
        $orderRepo = $this->em->getRepository(Order::class);
        /** @var Order[] $orders */
        $orders = $orderRepo->findWaitingOrdersForUser($user);

        /** @var OrderDto[] $ordersDto */
        $ordersDto = array_map(function (Order $order) {
            return $this->orderService->convertToDto($order);
        }, $orders);
        return $ordersDto;
    }

    /**
     * Récupère toutes les commandes en attente.
     *
     * @View()
     * @Get("/orders/waiting-all")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return OrderDto[]
     */
    public function allWaitingOrdersAction(Request $request)
    {
        $orderRepo = $this->em->getRepository(Order::class);
        /** @var Order[] $orders */
        $orders = $orderRepo->findWaitingOrders();

        /** @var OrderDto[] $ordersDto */
        $ordersDto = array_map(function (Order $order) {
            return $this->orderService->convertToDto($order, true);
        }, $orders);
        return $ordersDto;
    }

    /**
     * Supprime une commande.
     *
     * @View()
     * @Delete("/orders/{id}", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param UserInterface $user
     * @param string $id
     * @return OrderDto
     */
    public function deleteOrderAction(Request $request, UserInterface $user, string $id)
    {
        $orderRepo = $this->em->getRepository(Order::class);
        /** @var Order $order */
        $order = $orderRepo->find($id);

        if ($order->getUser()->getUsername() !== $user->getUsername() && !in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new \Exception('Vous n\'avez pas le droit de supprimer cette commande.');
        }

        $this->em->remove($order);
        $this->em->flush();

        /** @var OrderDto $orderDto */
        $orderDto = $this->orderService->convertToDto($order);
        return $orderDto;
    }

    /**
     * Valide toutes les commandes en attente.
     *
     * @View()
     * @Post("/orders/waiting-all/validate")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return OrderDto[]
     */
    public function validateAllWaitingOrdersAction(Request $request)
    {
        $orderRepo = $this->em->getRepository(Order::class);
        /** @var Order[] $orders */
        $orders = $orderRepo->findWaitingOrders();

        $now = new \DateTime();
        foreach ($orders as $order)
        {
            $order->setIsWaiting(false);
            $order->setValidationDate($now);
            $this->em->persist($order);
        }

        $this->em->flush();

        /** @var OrderDto[] $ordersDto */
        $ordersDto = array_map(function (Order $order) {
            return $this->orderService->convertToDto($order, true);
        }, $orders);
        return $ordersDto;
    }

    /**
     * Exporte toutes les commandes en attente au format CSV.
     *
     * @View()
     * @Get("/orders/waiting-all/export")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request
     * @return StreamedResponse
     */
    public function exportAllWaitingOrdersAction(Request $request)
    {
        $orderRepo = $this->em->getRepository(Order::class);
        /** @var Order[] $orders */
        $orders = $orderRepo->findWaitingOrders();

        $filename = 'export_commandes_' . date('d_m_Y') . '.csv';
        $response = new StreamedResponse();
        $response->setCallback(function () use ($orders) {
            $handle = fopen('php://output', 'w+');
            // Nom des colonnes du CSV

            /** @var string[] $users */
            $users = array_unique(array_map(function (Order $order) {
                return $order->getUser()->getUsername();
            }, $orders));
            $columns = array('', '', '');
            foreach ($users as $user) {
                $columns[] = $user;
            }

            fputcsv($handle, array_map('utf8_decode', array_map(function (string $column){
                $explode = explode('@', $column);
                $namePart = explode('.', $explode[0]);
                return join(' ', $namePart) . "";
            }, $columns)), ';');

            $coffeeList = [];
            foreach ($orders as $order) {
                /** @var OrderedCoffee $orderedCoffee */
                foreach ($order->getItems()->toArray() as $orderedCoffee) {
                    $coffeeList[] = $orderedCoffee->getCoffee();
                }
            }

            $coffeeList = array_unique($coffeeList, SORT_REGULAR );
            $lines = [];
            $mergedOrders = $this->orderService->mergeOrdersPerUsers($orders);
            $totals = array('Total', '', '');
            /** @var Coffee $coffee */
            foreach ($coffeeList as $coffee) {
                $line1 = array($coffee->getName(), 'Boîte (x50)', $coffee->getUnitPrice());
                $line2 = array($coffee->getDesc(), 'Tube (x30)', $coffee->getUnitPrice());
                /** @var Order $order */
                foreach ($mergedOrders as $order) {
                    /** @var OrderedCoffee $orderedCoffee */
                    $found = false;
                    foreach ($order->getItems() as $orderedCoffee) {
                        if ($orderedCoffee->getCoffee() === $coffee) {
                            $line1[] = $orderedCoffee->getQuantity50() ? $orderedCoffee->getQuantity50() : '';
                            $line2[] = $orderedCoffee->getQuantity30() ? $orderedCoffee->getQuantity30() : '';
                            $found = true;
                        }
                    }
                    if (!$found) {
                        $line1[] = '';
                        $line2[] = '';
                    }
                }
                $lines[] = $line1;
                $lines[] = $line2;
                $lines[] = [];
            }

            $totalGlobal = 0;
            foreach ($mergedOrders as $mergedOrder) {
                $total = 0;
                foreach ($mergedOrder->getItems() as $item) {
                    $total += ($item->getQuantity30() * 30 * $item->getCoffee()->getUnitPrice()) + ($item->getQuantity50() * 50 * $item->getCoffee()->getUnitPrice());
                }

                $totalGlobal += $total;
                $totals[] = str_replace('.', ',', $total);
            }

            //Champs
            foreach ($lines as $line) {
                fputcsv($handle, array_map('utf8_decode', $line), ';');
            }
            fputcsv($handle, array_map('utf8_decode', $totals), ';');
            fputcsv($handle, array_map('utf8_decode', array('Total global', str_replace('.', ',', $totalGlobal))), ';');
            fclose($handle);
        });

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        return $response;
    }
}