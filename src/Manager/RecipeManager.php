<?php
declare(strict_types=1);

namespace App\Manager;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Environment;

/**
 * RecipeManager
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Manager
*/
class RecipeManager
{
    /**
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     * @param RecipeRepository $recipeRepository
     * @param Environment $twig
    */
    public function __construct(
        protected EntityManagerInterface $em,
        protected FormFactoryInterface $formFactory,
        protected RecipeRepository $recipeRepository,
        protected Environment $twig
    )
    {


    }





    /**
     * @param int $duration
     * @return Recipe[]
    */
    public function findRecipesWithDurationLowerThan(int $duration): array
    {
        return $this->recipeRepository->findWithDurationLowerThan($duration);
    }





    /**
     * @param int $id
     * @param string $slug
     * @return Recipe|null
    */
    public function finOneRecipeBy(int $id, string $slug): ?Recipe
    {
        $recipe = $this->recipeRepository->find($id);

        if ($recipe->getSlug() !== $slug) {
             return null;
        }

        return $recipe;
    }





    public function createRecipeFromForm(): Recipe
    {
         //TODO implements
        return new Recipe();
    }



    /**
     * @param Recipe $recipe
     * @return Recipe
    */
    public function saveRecipe(Recipe $recipe): Recipe
    {
        $this->em->persist($recipe);
        $this->em->flush();
        return $recipe;
    }
}