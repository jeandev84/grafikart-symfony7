<?php
namespace App\Controller;

use App\DTO\ContactDTO;
use App\Event\ContactRequestEvent;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class ContactController extends AbstractController
{


    /**
     * @param EventDispatcherInterface $dispatcher
    */
    public function __construct(
        protected EventDispatcherInterface $dispatcher
    )
    {
    }



    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        $data = new ContactDTO();

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {

                $this->dispatcher->dispatch(new ContactRequestEvent($data));
                $this->addFlash('success', 'Votre message a bien ete envoye');
                return $this->redirectToRoute('contact');

            } catch (Throwable $e) {
                $this->addFlash('danger', "Impossible d' envoyer votre email.");
            }
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form
        ]);
    }
}
