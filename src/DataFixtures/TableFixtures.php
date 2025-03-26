<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Entity\Table;
use App\Entity\Zone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TableFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const TABLE_REFERENCE = 'table';


    public function load(ObjectManager $manager): void
    {
        $table = new Table();
        $table->setZone($this->getReference(ZoneFixtures::ZONE_REFERENCE, Zone::class));
        $table->setNumber(1);
        $table->setSeatsCount(4);
        
        $manager->persist($table);
        $manager->flush();
        
        $this->addReference(self::TABLE_REFERENCE, $table);
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }

    public function getDependencies(): array
    {
        return [
            ZoneFixtures::class,
        ];
    }
}
