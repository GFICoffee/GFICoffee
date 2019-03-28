<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class NotificationService
{
    /** @var Client */
    protected $httpClient;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var string */
    private $mailgunApiKey;

    public function __construct(ParameterBagInterface $parameterBag, EntityManagerInterface $em)
    {
      $this->httpClient = new Client();
      $this->em = $em;
      $this->mailgunApiKey = $parameterBag->get('mailgunApiKey');
    }

  /**
   * @param string $subject
   * @param string $message
   * @param User $to
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
    public function sendNotification(string $subject, string $message, User $to)
    {
      $multipart = new MultipartStream(array(
        array('name' => 'from', 'contents' => 'GFI Coffee <postmaster@sandbox9be9054794694104bb6e0ecb0b5a4f73.mailgun.org>'),
        array('name' => 'to', 'contents' => $to->getFirstname() . $to->getLastname() . '<' . $to->getEmail() .'>'),
        array('name' => 'subject', 'contents' => $subject),
        array('name' => 'text', 'contents' => $message)
      ));
      $request = new Request(
        'POST',
        'https://api.mailgun.net/v3/sandbox9be9054794694104bb6e0ecb0b5a4f73.mailgun.org/messages',
        array('Authorization' => 'Basic ' . base64_encode('api:'.$this->mailgunApiKey)),
        $multipart
      );
        return $this->httpClient->sendAsync($request);
    }

  /**
   * @param string $subject
   * @param string $message
   * @param User[] $to
   */
    public function sendNotifications(string $subject, string $message, array $to)
    {
      /** @var PromiseInterface[] $promises */
      $promises = [];
      foreach ($to as $user) {
        $promises[] = $this->sendNotification($subject, $message, $user);
      }

      foreach ($promises as $promise) {
        $promise->wait();
      }
    }

  /**
   * @param User[] $to
   */
    public function sendPickupNotification(array $to)
    {
      $subject = "Votre café est arrivé !";
      $recapTemplate = "%d %s: %g €\n";
      $messageTemplate = "Bonjour %s,\nVotre commande de café est arrivée, vous pouvez dès maintenant venir la régler et la récupérer.\n\nMontant: %g €\n\nRécapitulatif:\n";

      /** @var PromiseInterface[] $promises */
      $promises = [];
      foreach ($to as $user) {
        /** @var OrderRepository $orderRepo */
        $orderRepo = $this->em->getRepository(Order::class);
        /** @var Order[] $orders */
        $orders = $orderRepo->findOrdersToPayForUser($user);
        $recapMessage = "";
        $totalPrice = 0;
        foreach ($orders as $order) {
          foreach ($order->getItems() as $item) {
            $nbCapsules30 = $item->getQuantity30() * 30;
            $nbCapsules50 = $item->getQuantity50() * 50;
            $capsulesPrice = ($nbCapsules30 + $nbCapsules50) * $item->getCoffee()->getUnitPrice();
            $totalPrice += $capsulesPrice;
            $recapMessage .= sprintf($recapTemplate, $nbCapsules30 + $nbCapsules50, $item->getCoffee()->getName(), $capsulesPrice);
          }
        }
        $message = sprintf($messageTemplate, $user->getFirstname() . ' ' . $user->getLastname(), $totalPrice) . $recapMessage;
        $promises[] = $this->sendNotification($subject, $message, $user);
      }

      foreach ($promises as $promise) {
        $promise->wait();
      }
    }
}
