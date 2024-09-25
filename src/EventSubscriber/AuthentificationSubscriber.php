<?php


namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class AuthentificationSubscriber
{

    public function __construct(
        /* Injection de dépendances possible ici*/
        private RequestStack $requestStack
    ){}

    #[AsEventListener]
    public function eventSuccess(LoginSuccessEvent $loginSuccessEvent) {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('success', "Connexion réussie !");
    }

    #[AsEventListener]
    public function eventFailure(LoginFailureEvent $failureEvent): void{
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('error', "Login et/ou mot de passe incorrect !");
    }

    #[AsEventListener]
    public function eventLogout(LogoutEvent $logoutEvent): void{
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('success', "Déconnexion réussie !");
    }
}
