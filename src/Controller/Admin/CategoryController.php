<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

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
class CategoryController extends AbstractController
{


      #[Route(name: 'index')]
      public function index(): Response
      {

      }



      #[Route('/create', name: 'create')]
      public function create(): Response
      {

      }




      #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
      public function edit(): Response
      {

      }




      #[Route('/{id}', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
      public function delete(): Response
      {

      }
}