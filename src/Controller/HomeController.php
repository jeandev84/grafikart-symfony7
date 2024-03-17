<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{


    /**
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param Security $security
    */
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected Security $security
    )
    {
    }



    #[Route("/", name: "home")]
    # http://localhost:8000/?name=john
    public function index(Request $request): Response
    {
        /*
        $user = new User();
        $user->setEmail('john@doe.fr')
            ->setUsername('JohnDoe')
            ->setPassword($this->userPasswordHasher->hashPassword($user, '1234'))
            ->setRoles([]);

        $this->em->persist($user);
        $this->em->flush();
        */

        dump($this->getUser());
        dump($this->security->getUser());
        dump($this->security->getToken());
        dump($this->security->getToken()->getUser());

        return $this->render('home/index.html.twig');
    }
}
