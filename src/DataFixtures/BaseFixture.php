<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    private ObjectManager $manager;

    abstract protected function loadData(ObjectManager $manager);

    public function __construct(protected Generator $faker)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadData($manager);
        $manager->flush();
    }

    protected function createMany(string $className, int $count, callable $factory): void
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $i);
            $this->manager->persist($entity);
        }
    }
}
