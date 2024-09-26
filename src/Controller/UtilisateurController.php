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
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();
            $visible = $form->get('visible')->getData();
            $code = $form->get('code')->getData();
            $numTel = $form->get('numTelephone')->getData();

            $this->userManager->modifieUser($user, $password, $email, $visible, $code, $numTel);
            $this->entityManager->flush();
            $this->addFlash('success',"L'utilisateur a été modifié");
            return $this->redirectToRoute('app_home');
        }


        return $this->render('modification/modification_utilisateur.html.twig', [
            'modificationForm' => $form,
            'utilisateur' => $user,
        ]);

    }
    #[Route('/formSuppressionUtilisateur/{id}', name: 'app_form_removeUser')]
    #[IsGranted("ROLE_USER")]
    public function afficherFormSuppression(?Utilisateur $utilisateur): Response
    {

        $utilisateur = $this->getUser();
        return $this->render('modification/suppressionUtilisateur.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/supprimerUtilisateur/{id}', name: 'app_removeUser',options: ["expose" => true],methods: ['GET','DELETE'])]
    #[IsGranted("ROLE_USER")]
    public function removeUser(EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $this->getUser();
        $entityManager->remove($utilisateur);
        $entityManager->flush();
        $this->addFlash('success',"L'utilisateur a été supprimé");
        return $this->redirectToRoute('app_home');
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
}
