<?php

namespace App\Service;

use App\Service\BaseService;

class OrderService extends BaseService
{
    public function saveOrder($order) {
        $amount = 0.0;

        foreach ($order->getMenuItem() as $menuItem) {
            $amount += $menuItem->getPrice();
        }

        $order->setAmount($amount);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}