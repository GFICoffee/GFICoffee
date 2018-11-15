<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderedCoffee;
use App\Model\Coffee\CoffeeModel;
use App\Model\Coffee\OrderDto;
use App\Model\User\UserModel;

class OrderService
{
    /**
     * @param Order $order
     * @param bool $showUser
     * @return OrderDto
     */
    function convertToDto (Order $order, bool $showUser = false): OrderDto
    {
        $dto = new OrderDto();
        $dto->setId($order->getId());
        $dto->setItems(array_map(function (OrderedCoffee $item): CoffeeModel {
            $coffee = new CoffeeModel();
            $coffee->setId($item->getId());
            $coffee->setQuantity30($item->getQuantity30());
            $coffee->setQuantity50($item->getQuantity50());
            $coffee->setImg($item->getCoffee()->getImg());
            $coffee->setName($item->getCoffee()->getName());
            $coffee->setDesc($item->getCoffee()->getDesc());
            $coffee->setIntensity($item->getCoffee()->getIntensity());
            $coffee->setType($item->getCoffee()->getType());
            $coffee->setUnitPrice($item->getCoffee()->getUnitPrice());

            return $coffee;
        }, $order->getItems()->toArray()));

        if ($showUser) {
            $dto->setUser(new UserModel($order->getUser()));
        }

        return $dto;
    }

    /**
     * @param Order[] $orders
     * @return Order[]
     */
    function mergeOrdersPerUsers (array $orders): array {
        /**
         * @param OrderedCoffee[] $list
         * @param OrderedCoffee $coffee
         * @return bool|mixed
         */
        function userHasAlreadyOrderedCoffee (array $list, OrderedCoffee $coffee) {
            foreach ($list as $key => $item) {
                if ($item->getCoffee()->getId() === $coffee->getCoffee()->getId()) {
                    return $key;
                }
            }
            return false;
        }
        /** @var Order[] $mergedOrders */
        $mergedOrders = array();

        /** @var Order $order */
        foreach ($orders as $order) {
            /** @var bool|integer $userHasAlreadyOrdered */
            $userHasAlreadyOrdered = false;
            /** @var Order $mergedOrder */
            foreach ($mergedOrders as $key => $mergedOrder) {
                if ($order->getUser()->getUsername() === $mergedOrder->getUser()->getUsername()) {
                    $userHasAlreadyOrdered = $key;
                    break;
                }
            }

            if ($userHasAlreadyOrdered === false) {
                $orderToAdd = $order;
                $orderToAdd->setItems(is_array($orderToAdd->getItems()) ? $orderToAdd->getItems() : $orderToAdd->getItems()->toArray());
                $mergedOrders[] = $orderToAdd;
            } else {
                /** @var OrderedCoffee[] $newList */
                $newList = $mergedOrders[$userHasAlreadyOrdered]->getItems();
                /** @var OrderedCoffee[] $items */
                $items = $order->getItems()->toArray();
                foreach ($newList as $item) {
                    $index = userHasAlreadyOrderedCoffee($items, $item);
                    if ($index !== false) {
                        $item->setQuantity30($item->getQuantity30() + $items[$index]->getQuantity30());
                        $item->setQuantity50($item->getQuantity50() + $items[$index]->getQuantity50());
                        unset($items[$index]);
                    }
                }

                if (count($items)) {
                    array_push($newList, ...$items);
                }
                $mergedOrders[$userHasAlreadyOrdered]->setItems($newList);
            }
        }
        return $mergedOrders;
    }
}