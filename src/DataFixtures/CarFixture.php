<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Voiture;

class CarFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {

            $car = new Voiture();

            $car->setMarque("La masque " . $i);

            $car->setCouleur("Le couleur " . $i);

            $car->setPrix(" 100.000 " . $i);

            $manager->persist($car);

        }

        $manager->flush();
    }
}
