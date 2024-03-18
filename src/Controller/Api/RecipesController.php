<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
        * @param EntityManagerInterface $em
        * @param RecipeRepository $recipeRepository
        * @param SerializerInterface $serializer
       */
       public function __construct(
           protected EntityManagerInterface $em,
           protected RecipeRepository $recipeRepository,
           protected SerializerInterface $serializer
       )
       {
       }



       #[Route("/api/recipes", methods: ['GET'])]
       # http://localhost:8000/api/recipes?page=3
       public function index(
           #[MapQueryString]
           ?PaginationDTO $paginationDTO = null
       ): JsonResponse
       {
           /*
           $recipes = $this->recipeRepository->paginateRecipes(
               $request->query->getInt('page', 1)
           );
           */

           # dd($paginationDTO);

           $recipes = $this->recipeRepository->paginateRecipes(
               $paginationDTO?->page
           );

           return $this->json($recipes, Response::HTTP_OK, [], [
               'groups' => ['recipes.index']
           ]);
       }



    #[Route("/api/recipes/{id}", requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    # http://localhost:8000/api/recipes/2
    public function show(Recipe $recipe): JsonResponse
    {
        return $this->json($recipe, Response::HTTP_OK, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }






    #[Route("/api/recipes", methods: ['POST'])]
    # http://localhost:8000/api/recipes
    public function create(
        Request $request,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['recipes.create']
            ]
        )]
        Recipe $recipe
    ): JsonResponse
    {

        $recipe->setCreatedAt(new DateTimeImmutable())
               ->setUpdatedAt(new DateTimeImmutable());

        $this->em->persist($recipe);
        $this->em->flush();

        return $this->json($recipe, Response::HTTP_OK, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }
}