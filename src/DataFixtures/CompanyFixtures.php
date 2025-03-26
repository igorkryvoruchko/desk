<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CompanyFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const COMPANY_REFERENCE = 'company';


    public function load(ObjectManager $manager): void
    {
        $company = new Company();
        $company->setAlias('best_burgers');
        $company->setLogo('logo.jpg');
        $company->translate('en')->setName('Best Burgers');
        $company->translate('de')->setName('Am bestens Burgers');

        $manager->persist($company);
        $company->mergeNewTranslations();
        $manager->flush();
        
        $this->addReference(self::COMPANY_REFERENCE, $company);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
