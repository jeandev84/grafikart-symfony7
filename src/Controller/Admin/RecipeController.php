<?php
namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Twig\Environment;


#[Route('/admin/recipes', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{


    /**
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     * @param RecipeRepository $recipeRepository
     * @param CategoryRepository $categoryRepository
     * @param Environment $twig
    */
    public function __construct(
        protected EntityManagerInterface $em,
        protected FormFactoryInterface $formFactory,
        protected RecipeRepository $recipeRepository,
        protected CategoryRepository $categoryRepository,
        protected Environment $twig
    )
    {
    }



    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        /*
        $platPrincipal = $this->categoryRepository->findOneBy(['slug' => 'plat-principal']);
        $pates         = $this->recipeRepository->findOneBy(['slug' => 'pates-bolognaises']);
        $pates->setCategory($platPrincipal);
        $this->em->flush();
        */


        $recipes = $this->recipeRepository->findWithDurationLowerThan(20);

        /*
        $category = (new Category())
                    ->setUpdatedAt(new \DateTimeImmutable())
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setName('demo')
                    ->setSlug('demo');
        $this->em->persist($category); // resolve cascade persist
        $recipes[0]->setCategory($category);
        $this->em->flush();
        */

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }




    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $recipe = new Recipe();

        $form  = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($recipe);
            $this->em->flush();
            $this->addFlash('success', 'La recette a bien ete cree');
            return $this->redirectToRoute('admin.recipe.index');
        }

        /* $this->twig->render('recipe/create.html.twig'); */

        return $this->render('admin/recipe/create.html.twig', [
            'form'    => $form
        ]);
    }








    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request): Response
    {
         $form = $this->formFactory->create(RecipeType::class, $recipe);

         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {

             $this->em->flush();
             $this->addFlash('success', 'La recette a bien ete modifiee');
             return $this->redirectToRoute('admin.recipe.index');
         }

         return $this->render('admin/recipe/edit.html.twig', [
             'recipe'  => $recipe,
             'form'    => $form, // recipe_form
         ]);
    }







    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function remove(Recipe $recipe): RedirectResponse
    {
         $this->em->remove($recipe);
         $this->em->flush();
         $this->addFlash('success', 'La recette a bien ete supprimee');
         return $this->redirectToRoute('admin.recipe.index');
    }
}
