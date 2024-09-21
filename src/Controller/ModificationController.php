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
            return $this->redirectToRoute('app_home');
        }

        return $this->render('modification/modification_utilisateur.html.twig', [
            'modificationForm' => $form,
        ]);

    }
    #[Route('/formSuppressionUtilisateur/{id}', name: 'app_removeUser',options: ["expose" => true],methods: ["GET"])]
    #[IsGranted("ROLE_USER")]
    public function afficherFormSuppression(Request $request, ?Utilisateur $utilisateur): Response
    {

        $utilisateur = $this->getUser();
        return $this->render('modification/suppressionUtilisateur.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
}
