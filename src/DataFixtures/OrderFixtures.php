<?php

namespace App\DataFixtures;

use App\Entity\KindMenu;
use App\Entity\MenuItem;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Entity\User;
use App\Entity\Zone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const ORDER_REFERENCE = 'order';


    public function load(ObjectManager $manager): void
    {
        $order = new Order();
        $order->setOrderedTable($this->getReference(TableFixtures::TABLE_REFERENCE, Table::class));
        $order->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE, User::class));
        $order->addMenuItem($this->getReference(MenuItemFixtures::MENU_ITEM_REFERENCE, MenuItem::class));
        $order->setTimeFrom(new \DateTimeImmutable());
        $order->setComment("Ohne Eis bitte!");

        $manager->persist($order);
        $manager->flush();
        
        $this->addReference(self::ORDER_REFERENCE, $order);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TableFixtures::class,
            MenuItemFixtures::class
        ];
    }
}
