<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Translation\CityTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CityFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const CITY_REFERENCE = 'city';

    public const CITY_ALIAS = 'potsdam';

    public function load(ObjectManager $manager): void
    {
        $cities = [
            self::CITY_ALIAS => [
                'en' => 'Potsdam',
                'de' => 'Potsdam',
            ],
            "berlin" => [
                'en' => 'Berlin',
                'de' => 'Berlin',
            ],
            "hamburg" => [
                'en' => 'Hamburg',
                'de' => 'Hamburg',
            ],
            "muenchen" => [
                'en' => 'Munich',
                'de' => 'München',
            ],
            "koeln" => [
                'en' => 'Cologne',
                'de' => 'Köln',
            ],
            "frankfurt" => [
                'en' => 'Frankfurt',
                'de' => 'Frankfurt',
            ],
            "stuttgart" => [
                'en' => 'Stuttgart',
                'de' => 'Stuttgart',
            ],
            "duesseldorf" => [
                'en' => 'Dusseldorf',
                'de' => 'Düsseldorf',
            ],
            "leipzig" => [
                'en' => 'Leipzig',
                'de' => 'Leipzig',
            ],
            "dresden" => [
                'en' => 'Dresden',
                'de' => 'Dresden',
            ],
            "nuernberg" => [
                'en' => 'Nuremberg',
                'de' => 'Nürnberg',
            ],
            "bremen" => [
                'en' => 'Bremen',
                'de' => 'Bremen',
            ],
            "hannover" => [
                'en' => 'Hanover',
                'de' => 'Hannover',
            ],
            "karlsruhe" => [
                'en' => 'Karlsruhe',
                'de' => 'Karlsruhe',
            ],
            "freiburg" => [
                'en' => 'Freiburg',
                'de' => 'Freiburg',
            ],
            "heidelberg" => [
                'en' => 'Heidelberg',
                'de' => 'Heidelberg',
            ],
            "mannheim" => [
                'en' => 'Mannheim',
                'de' => 'Mannheim',
            ],
        ];

        foreach ($cities as $alias => $cityData) {
            $city = new City();
            $city->setAlias($alias)
                ->setCountry($this->getReference(CountryFixtures::COUNTRY_REFERENCE, Country::class));

            $translation = new CityTranslation();
            $translation->setLocale('en')->setName($cityData['en']);
            
            $translationDe = new CityTranslation();
            $translationDe->setLocale('de')->setName($cityData['de']);
            
            $city->addTranslation($translation);
            $city->addTranslation($translationDe);

            $this->addReference(self::CITY_REFERENCE . "_". $alias, $city);

            $manager->persist($city);
        }
        
        $manager->flush();
        
        $this->addReference(self::CITY_REFERENCE, $city);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }

    public function getDependencies(): array
    {
        return [
            CountryFixtures::class,
        ];
    }
}
