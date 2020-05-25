<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i=0;$i<100;$i++){
            $property = new Property();
            $property
                ->setTitle($faker->word(3, true))
                ->setDescription($faker->sentence(3, true))
                ->setSurface($faker->numberBetween(20, 350))
                ->setRooms($faker->numberBetween(2,10))
                ->setBedrooms($faker->numberBetween(1, 9))
                ->setFloor($faker->numberBetween(0, 10))
                ->setPrice($faker->numberBetween(50000, 800000))
                ->setCity($faker->city)
                ->setAdress($faker->address)
                ->setCodePostal($faker->postcode)
                ->setSold(false);
            $manager->persist($property);
        }
        $manager->flush();
    }
}
