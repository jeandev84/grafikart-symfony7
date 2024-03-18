<?php
namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\ContactRequestEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class MailingSubscriber implements EventSubscriberInterface
{


    /**
     * @param MailerInterface $mailer
    */
    public function __construct(
        protected readonly MailerInterface $mailer
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class    => 'onContactRequestEvent',
            InteractiveLoginEvent::class  => 'onLogin'
        ];
    }





    /**
     * @param ContactRequestEvent $event
     * @return void
     * @throws TransportExceptionInterface
    */
    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
        $data = $event->contactDTO;

        $message = (new TemplatedEmail())
            ->to($data->service)
            ->from($data->email)
            ->subject('Demande de contact')
            ->htmlTemplate('emails/contact.html.twig')
            ->context(['data' => $data]);

        $this->mailer->send($message);
    }


    /**
     * Envoie un email a chaque fois que nous somme authentifie
     *
     * @param InteractiveLoginEvent $event
     * @return void
     * @throws TransportExceptionInterface
     */
    public function onLogin(InteractiveLoginEvent $event): void
    {
         $user = $event->getAuthenticationToken()->getUser();
         if (!$user instanceof User) {
             return;
         }

        $message = (new Email())
            ->to($user->getEmail())
            ->from('support@demo.fr')
            ->subject('Connexion')
            ->text('Vous vous etes connecte');

        $this->mailer->send($message);
    }
}
