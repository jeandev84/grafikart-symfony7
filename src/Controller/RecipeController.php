<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe.index')]
    # http://localhost:8000/recipe
    public function index(Request $request): Response
    {
          return new Response('Recettes');
    }




    #[Route('/recipe/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    # http://localhost:8000/recipe/pate-bolognaise-32
    public function show(Request $request, string $slug, int $id): Response
    {
        /*
        dump(
            $request->attributes->get('slug'),
            $request->attributes->getInt('id')
        );

        dump($request);
        dump($slug, $id);

        return new JsonResponse([
            'slug' => $slug
        ]);

        # return new Response("Recette : ". $slug);
        */

        return $this->json([
            'slug' => $slug
        ]);
    }
}
