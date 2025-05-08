<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Translation\CompanyTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CompanyFixtures extends Fixture implements FixtureGroupInterface
{
    public const COMPANY_REFERENCE = 'company';
    public const COMPANY_ALIAS = 'best_burgers';


    public function load(ObjectManager $manager): void
    {
        $company = new Company();
        $company->setAlias(self::COMPANY_ALIAS)
            ->setLogo('logo.jpg');

        $translation = new CompanyTranslation();
        $translation->setLocale('en')
            ->setName('Best Burgers');
        
        $translationDe = new CompanyTranslation();
        $translationDe->setLocale('de')
            ->setName('Am bestens Burgers');
        
        
        $company->addTranslation($translation);
        $company->addTranslation($translationDe);

        $manager->persist($company);
        $manager->flush();
        
        $this->addReference(self::COMPANY_REFERENCE, $company);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}
