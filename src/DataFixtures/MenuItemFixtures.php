<?php

namespace App\DataFixtures;

use App\Entity\KindMenu;
use App\Entity\MenuItem;
use App\Entity\Translation\MenuItemTranslation;
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

        $translation = new MenuItemTranslation();
        $translation->setLocale('en');
        $translation->setName('BigMak');
        $translation->setDescription('BigMak sandwich');
        

        $translationDe = new MenuItemTranslation();
        $translationDe->setLocale('de');
        $translationDe->setName('BigMak');
        $translationDe->setDescription('BigMak sandwich');

        $menuItem->addTranslation($translation);
        $menuItem->addTranslation($translationDe);

        $menuItem->setPrice(5.99);
        $menuItem->setSpecialPrice(4.99);
        $menuItem->setQuantity(10);
        $menuItem->setPhoto('bigmak.jpg');

        $manager->persist($menuItem);
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
