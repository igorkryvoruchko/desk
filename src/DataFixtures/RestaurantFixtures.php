<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Company;
use App\Entity\Restaurant;
use App\Entity\Translation\RestaurantTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RestaurantFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const RESTAURANT_REFERENCE = 'restaurant';

    public const RESTAURANT_ALIAS = 'potsdam_best_burger';


    public function load(ObjectManager $manager): void
    {
        $restaurant = new Restaurant();
        $restaurant->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE, Company::class));
        $restaurant->setAlias(self::RESTAURANT_ALIAS);
        $restaurant->setCity($this->getReference('city_potsdam', City::class));
        $restaurant->setAddress('Grossbeerenstr. 1'); 
        $restaurant->setType('Fast Food');
        $restaurant->setPostalCode('14467');

        $translate = new RestaurantTranslation();
        $translate->setName('Best Burgers Potsdam');
        $translate->setDescription('The best burgers in Potsdam');
        $translate->setLocale('en');

        $translateDe = new RestaurantTranslation();
        $translateDe->setName('Am besten Burgers Potsdam');
        $translateDe->setDescription('Die besten Burger in Potsdam');
        $translateDe->setLocale('de');

        $restaurant->addTranslation($translate);
        $restaurant->addTranslation($translateDe);

        $manager->persist($restaurant);
        $manager->flush();
        
        $this->addReference(self::RESTAURANT_REFERENCE, $restaurant);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }

    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
            CityFixtures::class,
        ];
    }
}
