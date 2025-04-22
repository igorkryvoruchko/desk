<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    public const USER_EMAIL = 'user@example.com';

    public const USER_PASSWORD = 'user1234';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('User');
        $user->setEmail(self::USER_EMAIL);
        $password = $this->hasher->hashPassword($user, self::USER_PASSWORD);
        $user->setPassword($password);
        $user->setLocale('en');
        $user->setRating(10);
        $user->setCity($this->getReference('city_potsdam', City::class));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE, Company::class));

        $manager->persist($user);
        $manager->flush();
        
        $this->addReference(self::ADMIN_USER_REFERENCE, $user);
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
