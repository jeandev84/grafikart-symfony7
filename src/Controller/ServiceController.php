<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Recipe;
use App\Service\DemoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * ServiceController
 *
 * @author Jean-Claude <jeanyao@ymail.com>
 *
 * @license https://github.com/jeandev84/laventure-framework/blob/master/LICENSE
 *
 * @package  App\Controller
*/
class ServiceController extends AbstractController
{

      #[Route('/bash')]
      public function demo(
          DemoService $demoService,
          ValidatorInterface $validator
      ): void
      {
          $recipe = new Recipe();
          $errors = $validator->validate($recipe);
          dd((string)$errors);
      }
}