<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * RecipesController
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Controller\Api
*/
class RecipesController extends AbstractController
{


       /**
        * @param RecipeRepository $recipeRepository
        * @param SerializerInterface $serializer
       */
       public function __construct(
           protected RecipeRepository $recipeRepository,
           protected SerializerInterface $serializer
       )
       {
       }



       #[Route("/api/recipes")]
       # http://localhost:8000/api/recipes
       public function index(Request $request): JsonResponse
       {
           $recipes = $this->recipeRepository->paginateRecipes(
               $request->query->getInt('page', 1)
           );

           /*
           dd($this->serializer->serialize($recipes, 'csv', [
               'groups' => ['recipes.index']
           ]));

           dd($this->serializer->serialize($recipes, 'xml', [
               'groups' => ['recipes.index']
           ]));

           dd($this->serializer->serialize($recipes, 'yaml', [
               'groups' => ['recipes.index']
           ]));

           */


           return $this->json($recipes, Response::HTTP_OK, [], [
               'groups' => ['recipes.index']
           ]);
       }



    #[Route("/api/recipes/{id}", requirements: ['id' => Requirement::DIGITS])]
    # http://localhost:8000/api/recipes/2
    public function show(Recipe $recipe): JsonResponse
    {
        return $this->json($recipe, Response::HTTP_OK, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }
}