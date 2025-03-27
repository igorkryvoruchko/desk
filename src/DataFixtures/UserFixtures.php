<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('Ihor');
        $user->setEmail('ihor@gmail.com');
        $password = $this->hasher->hashPassword($user, 'ihor1234');
        $user->setPassword($password);
        $user->setLocale('en');
        $user->setRating(10);
        $user->setLocation('Potsdam');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE));

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
        ];
    }
}
