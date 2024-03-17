<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * CategoryController
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Controller\Admin
*/
#[Route('/admin/category', name: 'admin.category.')]
#[IsGranted('ROLE_ADMIN')]
class CategoryController extends AbstractController
{


      public function __construct(
          protected EntityManagerInterface $em
      )
      {
      }



      #[Route(name: 'index')]
      public function index(CategoryRepository $categoryRepository): Response
      {
          /* dd($categoryRepository->findAllWithCount()); */

          return $this->render('admin/category/index.html.twig', [
              'categories' => $categoryRepository->findAllWithCount()
          ]);
      }



      #[Route('/create', name: 'create')]
      public function create(Request $request): Response
      {
          $category = new Category();

          $form = $this->createForm(CategoryType::class, $category);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
               $this->em->persist($category);
               $this->em->flush();
               $this->addFlash('success', "La categorie a bien ete cree");
               return $this->redirectToRoute('admin.category.index');
          }

          return $this->render('admin/category/create.html.twig', [
              'form' => $form
          ]);
      }




      #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
      public function edit(Request $request, Category $category): Response
      {
          $form = $this->createForm(CategoryType::class, $category);
          $form->handleRequest($request);

          if ($form->isSubmitted() && $form->isValid()) {
              $this->em->flush();
              $this->addFlash('success', "La categorie a bien ete modifiee");
              return $this->redirectToRoute('admin.category.index');
          }

          return $this->render('admin/category/edit.html.twig', [
              'category' => $category,
              'form'     => $form
          ]);
      }




      #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
      public function remove(Category $category): Response
      {
           $this->em->remove($category);
           $this->em->flush();
           $this->addFlash('success', "La categorie a bien ete supprimee");
           return $this->redirectToRoute('admin.category.index');
      }
}