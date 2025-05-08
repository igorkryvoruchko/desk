<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Translation\CountryTranslation;
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

        $translation = new CountryTranslation();
        $translation->setName('Germany');
        $translation->setLocale('en');

        $translationDe = new CountryTranslation();
        $translationDe->setName('Deutschland');
        $translationDe->setLocale('de');

        $country->addTranslation($translation);
        $country->addTranslation($translationDe);

        $manager->persist($country);
        $manager->flush();
        
        $this->addReference(self::COUNTRY_REFERENCE, $country);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}
