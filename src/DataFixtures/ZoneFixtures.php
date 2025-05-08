<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\Translation\ZoneTranslation;
use App\Entity\Zone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ZoneFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const ZONE_REFERENCE = 'zone';

    public const ZONE_ALIAS = 'inside';


    public function load(ObjectManager $manager): void
    {
        $zone = new Zone();
        $zone->setAlias(self::ZONE_ALIAS);
        $zone->setRestaurant($this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE, Restaurant::class));

        $translate = new ZoneTranslation();
        $translate->setName('Inside');
        $translate->setLocale('en');

        $translateDe = new ZoneTranslation();
        $translateDe->setName('Innen');
        $translateDe->setLocale('de');

        $zone->addTranslation($translate);
        $zone->addTranslation($translateDe);

        $manager->persist($zone);
        $manager->flush();
        
        $this->addReference(self::ZONE_REFERENCE, $zone);
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
