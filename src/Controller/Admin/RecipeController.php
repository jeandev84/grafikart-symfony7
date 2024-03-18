<?php
namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Security\Voter\RecipeVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Environment;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;


#[Route('/admin/recipes', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{


    /**
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     * @param RecipeRepository $recipeRepository
     * @param CategoryRepository $categoryRepository
     * @param Environment $twig
     * @param UploaderHelper $uploaderHelper
     * @param Security $security
    */
    public function __construct(
        protected EntityManagerInterface $em,
        protected FormFactoryInterface $formFactory,
        protected RecipeRepository $recipeRepository,
        protected CategoryRepository $categoryRepository,
        protected Environment $twig,
        protected UploaderHelper $uploaderHelper,
        protected Security $security
    )
    {
    }



    #[Route('/', name: 'index')]
    #[IsGranted(RecipeVoter::LIST)]
    public function index(Request $request): Response
    {
        $page       = $request->query->getInt('page', 1);
        $userId     = $this->security->getUser()->getId();
        $canListAll = $this->security->isGranted(RecipeVoter::LIST_ALL);
        $recipes    = $this->recipeRepository->paginateRecipes($page, $canListAll ? null : $userId);

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }




    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted(RecipeVoter::CREATE)]
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

        return $this->render('admin/recipe/create.html.twig', [
            'form'    => $form
        ]);
    }








    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
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
             'form'    => $form
         ]);
    }







    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function remove(Recipe $recipe): RedirectResponse
    {
         $this->em->remove($recipe);
         $this->em->flush();
         $this->addFlash('success', 'La recette a bien ete supprimee');
         return $this->redirectToRoute('admin.recipe.index');
    }
}
