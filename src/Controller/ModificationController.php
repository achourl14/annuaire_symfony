<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\AppUserAuthentificatorAuthenticator;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ModificationController extends AbstractController
{
    #[Route('/modification', name: 'app_modification')]
    public function register(Request $request, Security $security, EntityManagerInterface $entityManager, UserManagerInterface $userManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */

            $email = $form->get('email')->getData();
            $password = $form->get('plainPassword')->getData();
            $visible = $form->get('visible')->getData();
            $code = $form->get('code')->getData();

            $userManager->initialieUser($user, $email, $password, $visible, $code);

            $entityManager->persist($user);
            $entityManager->flush();

            return $security->login($user, AppUserAuthentificatorAuthenticator::class, 'main');
        }

        return $this->render('modification/modification_utilisateur.html.twig', [
            'modificationForm' => $form,
        ]);
    }
}
