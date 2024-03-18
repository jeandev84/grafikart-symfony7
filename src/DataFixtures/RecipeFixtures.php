<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @param SluggerInterface $slugger
    */
    public function __construct(
        private readonly SluggerInterface $slugger
    )
    {
    }




    /**
     * @param ObjectManager $manager
     * @return void
    */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $categories = ['Plat chaud', 'Dessert', 'Entree', 'Gouter'];

        foreach ($categories as $categoryName) {
            $category = (new Category())
                         ->setName($categoryName)
                         ->setSlug($this->slugger->slug($categoryName))
                         ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                         ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
            ;

            $manager->persist($category);
            $this->addReference($categoryName, $category);
        }

        for ($i = 1; $i <= 10; $i++) {
            $title  = $faker->foodName();
            $recipe = (new Recipe())
                      ->setTitle($title)
                      ->setSlug($this->slugger->slug($title))
                      ->setUpdatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                      ->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTime()))
                      ->setContent($faker->paragraphs(10, true))
                      ->setCategory($this->getReference($faker->randomElement($categories)))
                      ->setUser($this->getReference("USER". $faker->numberBetween(1, 10)))
                      ->setDuration($faker->numberBetween(2, 60));
            $manager->persist($recipe);
        }

        $manager->flush();
    }



    /**
     * @inheritDoc
    */
    public function getDependencies()
    {
        // On precise ici que ma fixture RecipeFixtures
        // depend de UserFixtures::class pour etre lancer
        // Donc RecipeFixtures::class depend de UserFixtures::class
        return [UserFixtures::class];
    }
}
