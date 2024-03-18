<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;

class RecipeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        for ($i = 1; $i <= 10; $i++) {
            $recipe = (new Recipe())
                      ->setTitle($faker->foodName())
                      ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                      ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                      ->setContent($faker->paragraphs(10, true))
                      ->setDuration($faker->numberBetween(2, 60));
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
