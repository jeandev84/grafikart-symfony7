<?php
namespace App\EventListener;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\LocaleSwitcher;

final class UserLocaleListener
{


    /**
     * @param Security $security
     * @param LocaleSwitcher $localeSwitcher
    */
    public function __construct(
        private readonly Security $security,
        private readonly LocaleSwitcher $localeSwitcher
    )
    {
    }



    ##[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
         /** @var User $user */
         $user = $this->security->getUser();

         if ($user instanceof UserInterface) {
              $this->localeSwitcher->setLocale($user->getLocale());
         }
    }
}
