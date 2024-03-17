<?php
namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class RecipeController extends AbstractController
{


    /**
     * @param EntityManagerInterface $em
    */
    public function __construct(
        protected EntityManagerInterface $em
    )
    {
    }



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





    #[Route('/recipe/{id}/edit', name: 'recipe.edit')]
    public function edit(Recipe $recipe, Request $request): Response
    {
         $form = $this->createForm(RecipeType::class, $recipe);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $recipe->setUpdatedAt(new DateTimeImmutable());
             $this->em->flush();
             $this->addFlash('success', 'La recette a bien ete modifiee');
             return $this->redirectToRoute('recipe.index');
         }

         return $this->render('recipe/edit.html.twig', [
             'recipe'  => $recipe,
             'form'    => $form, // recipe_form
         ]);
    }




    #[Route('/recipe/create', name: 'recipe.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $recipe = new Recipe();

        $form  = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setCreatedAt(new DateTimeImmutable());
            $recipe->setUpdatedAt(new DateTimeImmutable());

            $this->em->persist($recipe);
            $this->em->flush();
            $this->addFlash('success', 'La recette a bien ete cree');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/create.html.twig', [
            'form'    => $form
        ]);
    }




    #[Route('/recipe/{id}', name: 'recipe.delete', methods: ['DELETE'])]
    public function remove(Recipe $recipe): RedirectResponse
    {
         $this->em->remove($recipe);
         $this->em->flush();
         $this->addFlash('success', 'La recette a bien ete supprimee');
         return $this->redirectToRoute('recipe.index');
    }
}
