<?php

namespace App\DataFixtures;

use App\Entity\KindMenu;
use App\Entity\Restaurant;
use App\Entity\Translation\KindMenuTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class KindMenuFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const KIND_MENU_REFERENCE = 'kind_menu';

    public const KIND_MENU_ALIAS = 'burgers';

    public function load(ObjectManager $manager): void
    {
        $kindMenu = new KindMenu();
        $kindMenu->setAlias(self::KIND_MENU_ALIAS);
        $kindMenu->setIsActive(true);
        $kindMenu->setRestaurant($this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE, Restaurant::class));

        $translation = new KindMenuTranslation();
        $translation->setName('Burgers');
        $translation->setDescription('Burgers');
        $translation->setLocale('en');

        $translationDe = new KindMenuTranslation();
        $translationDe->setName('Die Burger');
        $translationDe->setDescription('Die Burger');
        $translationDe->setLocale('de');

        $kindMenu->addTranslation($translation);
        $kindMenu->addTranslation($translationDe);

        $manager->persist($kindMenu);
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
