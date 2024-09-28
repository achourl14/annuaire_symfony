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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UtilisateurController extends AbstractController
{



// mettre en place une route qui permet de rendre visible ou pas un utilisateur

    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private Security $security,
        private EntityManagerInterface $entityManager,
        private UserManagerInterface $userManager
    )
    {

    }

    #[Route('/visible/{id}', name: 'app_visible', options: ["expose" => true], methods: ['POST'])]
    public function rendreVisibleInvisible(int $id): JsonResponse
    {
        $recupUser = $this->utilisateurRepository->find($id);

        if ($recupUser === $this->security->getUser()) {
            // Inverser la visibilité
            $recupUser->setVisible(!$recupUser->getVisible());

            // Sauvegarder dans la base de données
            $this->entityManager->persist($recupUser);
            $this->entityManager->flush();

            // Retourner la nouvelle visibilité
            return new JsonResponse(['visible' => $recupUser->getVisible()], 200);
        }

        // Si l'utilisateur n'a pas les droits
        return new JsonResponse(["message" => "Vous n'avez pas les droits pour effectuer cette action"], 403);
    }


    #[Route('/modification', name: 'app_modification')]
    #[IsGranted("ROLE_USER")]
    public function modify(Request $request): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->security->getUser();

        $form = $this->createForm(ModificationUtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('email')->getData() != null) {
                $email = $form->get('email')->getData();
            } else {
                $email = $user->getEmail();
            }
            if ($form->get('newPassword')->getData() != null) {
                $password = $form->get('newPassword')->getData();
            } else {
                $password = $form->get('oldPassword')->getData();
            }
            $visible = $form->get('visible')->getData();

            $numTel = $form->get('numTelephone')->getData();
            $fichierPhotoProfil = $form->get('profile')->getData();

            if ($form->get('code')->getData() !== '' && $form->get('code')->getData() !== null) {
                $code = $form->get('code')->getData();
                $this->userManager->modifieUser($user, $password, $email, $visible, $code, $numTel,$fichierPhotoProfil);
            }
            else {
                $this->userManager->modifieUser($user, $password, $email, $visible, null, $numTel,$fichierPhotoProfil);
            }


            $this->entityManager->flush();
            $this->addFlash('success',"L'utilisateur a été modifié");
            return $this->redirectToRoute('app_home');
        }


        return $this->render('utilisateur/modification_utilisateur.html.twig', [
            'modificationForm' => $form,
            'utilisateur' => $user,
        ]);

    }

    #[Route('/profil/{code}',name:'detailProfil',methods: 'GET')]
    public function pagePerso(string $code) : Response
    {
        $utilisateur = $this->utilisateurRepository->findOneBy(['code' => $code]);

        if($utilisateur == null) {
            $this->addFlash('error','Utilisateur inexistant !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('utilisateur/detail_profil.html.twig', [
            'controller_name' => 'ModificationController',
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/verification/edition/code/{code}', name: 'verifEditionCodeUser', options: ['expose' => true])]
    public function verificationEditionCodeUtilisateur(string $code): Response
    {
        $userExist = $this->utilisateurRepository->findOneBy(['code' => $code]);
        if ($userExist && $userExist != $this->security->getUser()) {
            return new JsonResponse(['error' => 'Code déjà utilisé'], 400);
        }
        return new JsonResponse([],204);
    }

    #[Route('/verification/creation/code/{code}', name: 'verifCreationCodeUser', options: ['expose' => true])]
    public function verificationCreationCodeUtilisateur(string $code): Response
    {
        $userExist = $this->utilisateurRepository->findOneBy(['code' => $code]);
        if ($userExist) {
            return new JsonResponse(['error' => 'Code déjà utilisé'], 400);
        }
        return new JsonResponse([],204);
    }

}
