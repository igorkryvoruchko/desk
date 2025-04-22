<?php

namespace App\DataFixtures;

use App\Entity\KindMenu;
use App\Entity\MenuItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MenuItemFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const MENU_ITEM_REFERENCE = 'menu_item';

    public const MENU_ITEM_ALIAS = 'test_menu_item';


    public function load(ObjectManager $manager): void
    {
        $menuItem = new MenuItem();
        $menuItem->setAlias(self::MENU_ITEM_ALIAS);
        $menuItem->setKindMenu($this->getReference(KindMenuFixtures::KIND_MENU_REFERENCE, KindMenu::class));
        $menuItem->translate('en')->setName('BigMak');
        $menuItem->translate('de')->setName('BigMak');
        $menuItem->translate('en')->setDescription('BigMak sandwich');
        $menuItem->translate('de')->setDescription('BigMak sandwich');
        $menuItem->setPrice(5.99);
        $menuItem->setSpecialPrice(4.99);
        $menuItem->setQuantity(10);
        $menuItem->setPhoto('bigmak.jpg');

        $manager->persist($menuItem);
        $menuItem->mergeNewTranslations();
        $manager->flush();
        
        $this->addReference(self::MENU_ITEM_REFERENCE, $menuItem);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }

    public function getDependencies(): array
    {
        return [
            KindMenuFixtures::class,
        ];
    }
}
