<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use App\Security\AppUserAuthentificatorAuthenticator;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{


    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
    )
    {
    }

    #[Route('/register', name: 'app_register')]
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
            $profile = $form->get('profile')->getData();

            $userManager->initialieUser($user, $password, $email, $visible, $code, $profile);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $security->login($user, AppUserAuthentificatorAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verification/code/{code}', name: 'verifCodeUser', options: ['expose' => true])]
    public function verificationCodeUtilisateur(string $code): Response
    {
        $userExist = $this->utilisateurRepository->findOneBy(['code' => $code]);
        if ($userExist) {
            return new JsonResponse(['error' => 'Code déjà utilisé'], 400);
        }
        return new JsonResponse([],204);
    }
}
