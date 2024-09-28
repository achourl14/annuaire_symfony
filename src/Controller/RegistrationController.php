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
        private UtilisateurRepository $utilisateurRepository, private readonly Security $security,
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

            $this->addFlash('success',"L'utilisateur a bien été créé");

            // do anything else you need here, like send an email

            return $security->login($user, AppUserAuthentificatorAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }


    #[Route('/verification/creation/email/{email}', name: 'verifCreationEmailUser', options: ['expose' => true])]
    public function verificationCreationEmailUtilisateur(string $email): Response
    {
        $userExist = $this->utilisateurRepository->findOneBy(['email' => $email]);
        if ($userExist) {
            return new JsonResponse(['error' => 'Email déjà utilisé'], 400);
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Email non valide'], 400);
        }
        return new JsonResponse([],204);
    }

    #[Route('/verification/edition/email/{email}', name: 'verifEditionEmailUser', options: ['expose' => true])]
    public function verificationEditionEmailUtilisateur(string $email): Response
    {
        $userExist = $this->utilisateurRepository->findOneBy(['email' => $email]);
        if ($userExist && $userExist != $this->security->getUser()) {
            return new JsonResponse(['error' => 'Email déjà utilisé'], 400);
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Email non valide'], 400);
        }
        return new JsonResponse([],204);
    }

    #[Route('/verification/login/{login}', name: 'verifLoginUser', options: ['expose' => true])]
    public function verificationLoginUtilisateur(string $login): Response
    {
        $userExist = $this->utilisateurRepository->findOneBy(['login' => $login]);
        if ($userExist) {
            return new JsonResponse(['error' => 'Login déjà utilisé'], 400);
        }
        return new JsonResponse([],204);
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
