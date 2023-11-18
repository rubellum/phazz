<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var Connection $conn */
        $conn = $manager->getConnection();

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
