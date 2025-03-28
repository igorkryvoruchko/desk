<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Company;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CityFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const CITY_REFERENCE = 'city';


    public function load(ObjectManager $manager): void
    {
        $cities = [
            "potsdam" => [
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
            $city->setCountry($this->getReference(CountryFixtures::COUNTRY_REFERENCE, Country::class));
            $city->setAlias($alias);
            $city->translate('en')->setName($cityData['en']);
            $city->translate('de')->setName($cityData['de']);
            $this->addReference(self::CITY_REFERENCE . "_". $alias, $city);

            $manager->persist($city);
            $city->mergeNewTranslations();
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
