<?php
declare(strict_types=1);

namespace App\Manager;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;

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
    public function __construct(
        protected EntityManagerInterface $em
    )
    {
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