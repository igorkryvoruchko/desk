<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CountryFixtures extends Fixture implements FixtureGroupInterface
{
    public const COUNTRY_REFERENCE = 'country';


    public function load(ObjectManager $manager): void
    {
        $country = new Country();
        $country->setAlias('deutschland');
        $country->translate('en')->setName('Germany');
        $country->translate('de')->setName('Deutschland');

        $manager->persist($country);
        $country->mergeNewTranslations();
        $manager->flush();
        
        $this->addReference(self::COUNTRY_REFERENCE, $country);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}
