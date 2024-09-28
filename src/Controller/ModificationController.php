<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ModificationUtilisateurType;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use App\Security\AppUserAuthentificatorAuthenticator;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ModificationController extends AbstractController
{
    #[Route('/modification', name: 'app_modification')]
    #[IsGranted("ROLE_USER")]
    public function modify(Request $request, Security $security, EntityManagerInterface $entityManager, UserManagerInterface $userManager): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $security->getUser();

        $form = $this->createForm(ModificationUtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $visible = $form->get('visible')->getData();
            $code = $form->get('code')->getData();
            $numTel = $form->get('numTelephone')->getData();

            $userManager->modifieUser($user, $password, $email, $visible, $code, $numTel);
            $entityManager->flush();
            $this->addFlash('success',"L'utilisateur a été modifié");
            return $this->redirectToRoute('app_home');
        }


        return $this->render('modification/modification_utilisateur.html.twig', [
            'modificationForm' => $form,
            'utilisateur' => $user,
        ]);
    }

    #[Route('/utilisateurs/{code}/supprimer', name: 'app_form_removeUser', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function afficherFormSuppression(?Utilisateur $utilisateur): Response
    {
        if ($utilisateur == null) {
            $this->addFlash('error',"L'utilisateur n'existe pas");
            return $this->redirectToRoute('app_home');
        }

        /** @var Utilisateur $loggedInUser */
        $loggedInUser = $this->getUser();
        if ($loggedInUser == null) {
            $this->addFlash('error', "Vous n'êtes pas connecté.");
            return $this->redirectToRoute('app_login');
        }

        if ($loggedInUser->isAdmin()) {
            if ($utilisateur->isAdmin() && $loggedInUser->getId() != $utilisateur->getId()) {
                $this->addFlash('error', "L'utilisateur que vous essayez de supprimer est également un administrateur.");
                return $this->redirectToRoute('app_home');
            }
        } else {
            if ($utilisateur->getId() != $loggedInUser->getId()) {
                $this->addFlash('error', "Vous n'avez pas la permission de supprimer un autre utilisateur.");
                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('utilisateur/suppressionUtilisateur.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/utilisateurs/{code}/supprimer', name: 'app_removeUser', options: ["expose" => true],methods: ['POST','DELETE'])]
    #[IsGranted("ROLE_USER")]
    public function removeUser(?Utilisateur $utilisateur, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($utilisateur == null) {
            $this->addFlash('error',"L'utilisateur n'existe pas");
            return $this->redirectToRoute('app_home');
        }

        /** @var Utilisateur $loggedInUser */
        $loggedInUser = $this->getUser();
        if ($loggedInUser == null) {
            $this->addFlash('error', "Vous n'êtes pas connecté.");
            return $this->redirectToRoute('app_login');
        }

        if ($loggedInUser->isAdmin()) {
            if ($utilisateur->isAdmin()) {
                $this->addFlash('error', "L'utilisateur que vous essayez de supprimer est également un administrateur.");
                return $this->redirectToRoute('app_home');
            }
        } else {
            if ($utilisateur->getId() != $loggedInUser->getId()) {
                $this->addFlash('error', "Vous n'avez pas la permission de supprimer un autre utilisateur.");
                return $this->redirectToRoute('app_home');
            }
        }

        if ($loggedInUser->getId() == $utilisateur->getId()) {
            $this->container->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();
            $this->addFlash('success',"Votre profil a été supprimé.");
            $entityManager->remove($utilisateur);
            $entityManager->flush();
            return $this->redirectToRoute('app_logout');
        }

        $entityManager->remove($utilisateur);
        $entityManager->flush();
        $this->addFlash('success',"L'utilisateur a été supprimé.");
        return $this->redirectToRoute('app_home');
    }
}
