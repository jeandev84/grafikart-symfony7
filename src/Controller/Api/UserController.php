<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * UserController
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Controller\Api
 */
class UserController extends AbstractController
{


     #[Route("/api/me", methods: ['GET'])]
     public function me(): JsonResponse
     {
          return $this->json([
              'message' => 'Bonjour'
          ]);
     }
}