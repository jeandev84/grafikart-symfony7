<?php
namespace App\Controller;

use App\Entity\Recipe;
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

    /**
     * @param EntityManagerInterface $em
    */
    public function __construct(protected EntityManagerInterface $em)
    {
    }


    #[Route('/recipe', name: 'recipe.index')]
    # http://localhost:8000/recipe
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        /*  $recipes = $this->em->getRepository(Recipe::class)->findWithDurationLowerThan(20); */
        $recipes = $recipeRepository->findWithDurationLowerThan(20); // 20 min

        dump($recipeRepository->findTotalDuration());

        /*
        $recipes[0]->setTitle("Pates bolognaise");
        $this->em->flush();

        $recipe = new Recipe();
        $recipe->setTitle('Barbe a papa')
               ->setSlug('barbe-papa')
               ->setContent('Mettez du sucre')
               ->setDuration(2)
               ->setCreatedAt(new DateTimeImmutable())
               ->setUpdatedAt(new DateTimeImmutable());


         $this->em->persist($recipe);
         $this->em->flush();


         $this->em->remove($recipes[0]);
         $this->em->flush();

        */

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
}
