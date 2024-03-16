<?php
namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class RecipeController extends AbstractController
{

    #[Route('/recipes', name: 'recipe.index')]
    # http://localhost:8000/recipe
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findWithDurationLowerThan(20);

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }




    #[Route('/recipe/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    # http://localhost:8000/recipe/pate-bolognaise-32
    public function show(Request $request, RecipeRepository $recipeRepository, string $slug, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', [
                'slug' => $recipe->getSlug(),
                'id'   => $recipe->getId()
            ]);
        }

        return $this->render('recipe/show.html.twig', [
           'recipe' => $recipe
        ]);
    }





    #[Route('/recipe/{id}/edit', name: 'recipe.edit', requirements: ['id' => '\d+'])]
    public function edit(Recipe $recipe): Response
    {
         $form = $this->createForm(RecipeType::class, $recipe);

         return $this->render('recipe/edit.html.twig', [
             'recipe'  => $recipe,
             'form'    => $form, // recipe_form
         ]);
    }
}
