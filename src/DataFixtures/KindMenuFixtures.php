<?php

namespace App\DataFixtures;

use App\Entity\KindMenu;
use App\Entity\Restaurant;
use App\Entity\Zone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class KindMenuFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const KIND_MENU_REFERENCE = 'kind_menu';


    public function load(ObjectManager $manager): void
    {
        $kindMenu = new KindMenu();
        $kindMenu->setAlias('burgers');
        $kindMenu->setIsActive(true);
        $kindMenu->setRestaurant($this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE, Restaurant::class));
        $kindMenu->translate('en')->setName('Burgers');
        $kindMenu->translate('de')->setName('Die Burger');
        $kindMenu->translate('en')->setDescription('Burgers');
        $kindMenu->translate('de')->setDescription('Die Burger');

        $manager->persist($kindMenu);
        $kindMenu->mergeNewTranslations();
        $manager->flush();
        
        $this->addReference(self::KIND_MENU_REFERENCE, $kindMenu);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
        ];
    }
}
