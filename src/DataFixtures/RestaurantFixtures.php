<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RestaurantFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const RESTAURANT_REFERENCE = 'restaurant';


    public function load(ObjectManager $manager): void
    {
        $restaurant = new Restaurant();
        $restaurant->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE, Company::class));
        $restaurant->setAlias('potsdam_best_burger');
        $restaurant->setAddress('Potsdam, Germany');
        $restaurant->setType('Fast Food');
        $restaurant->translate('en')->setName('Best Burgers Potsdam');
        $restaurant->translate('de')->setName('Am besten Burgers Potsdam');
        $restaurant->translate('en')->setDescription('The best burgers in Potsdam');
        $restaurant->translate('de')->setDescription('Die besten Burger in Potsdam');

        $manager->persist($restaurant);
        $restaurant->mergeNewTranslations();
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
        ];
    }
}
