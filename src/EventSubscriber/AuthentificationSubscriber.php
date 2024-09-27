<?php


namespace App\EventSubscriber;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Flex\Event\UpdateEvent;

class AuthentificationSubscriber
{

    public function __construct(
        /* Injection de dépendances possible ici*/
        private readonly RequestStack  $requestStack,
        private readonly EntityManagerInterface $entityManager,
    ){}

    #[AsEventListener]
    public function eventSuccessForm(FormEvent $formEvent): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $user = $formEvent->getData();
        if($user instanceof Utilisateur){
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        $flashBag->add('success', "Modification faite !");
    }


    #[AsEventListener]
    public function eventSuccess(LoginSuccessEvent $loginSuccessEvent): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $user = $loginSuccessEvent->getUser();
        if($user instanceof Utilisateur){
            $user->setConnectedAt(new \DateTimeImmutable());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
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
