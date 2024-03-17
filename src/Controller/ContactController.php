<?php
namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class ContactController extends AbstractController
{


    /**
     * @param MailerInterface $mailer
    */
    public function __construct(
        protected MailerInterface $mailer
    )
    {
    }



    #[Route('/contact', name: 'contact')]
    public function contact(Request $request): Response
    {
        $data = new ContactDTO();

        // TODO : remove this
        /*
        $data->name    = 'John Doe';
        $data->email   = 'john@doe.fr';
        $data->message = 'Super site';
        */

        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*
            $emails = (new Email())
                    ->to('contact@demo.fr')
                    ->from($data->email)
                    ->html('<h1>Hello</h1>');
            */

            try {

                $message = (new TemplatedEmail())
                           ->to($data->service)
                           ->from($data->email)
                           ->subject('Demande de contact')
                           ->htmlTemplate('emails/contact.html.twig')
                           ->context(['data' => $data]);

                $this->mailer->send($message);
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
