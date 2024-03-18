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
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{


    /**
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param Security $security
     * @param TranslatorInterface $translator
    */
    public function __construct(
        protected EntityManagerInterface $em,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected Security $security,
        protected TranslatorInterface $translator
    )
    {
    }



    #[Route("/", name: "home")]
    # http://localhost:8000/?name=john
    public function index(Request $request): Response
    {
        /* dd($this->translator->trans('Welcome')); */
        return $this->render('home/index.html.twig');
    }
}
