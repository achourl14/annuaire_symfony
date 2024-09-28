<?php

namespace App\Controller;

use App\Entity\ProfilFavoris;
use App\Repository\ProfilFavorisRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavorisController extends AbstractController
{


    public function __construct(
        private ProfilFavorisRepository $profilFavorisRepository,
        private UtilisateurRepository $utilisateurRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/liste/favoris', name: 'app_favoris')]
    public function index(): Response
    {
//        renvoie la liste des favoris de l'utilisateur connecté
        $favoris = $this->getUser()->getProfilFavoris();

        return $this->render('favoris/index.html.twig', [
            'controller_name' => 'FavorisController',
            'favoris' => $favoris
        ]);
    }

    #[Route('/ajouter/favoris/{id}', name: 'app_ajouter_favoris')]
    public function ajouterFavoris($id): Response
    {
//        ajoute un profil aux favoris de l'utilisateur connecté
        $profilFavoris = new ProfilFavoris();
        $profilFavoris->setUtilisateur($this->getUser());
//        verifier que le favoris n'est pas deja dans la liste
        if ($this->profilFavorisRepository->findOneBy(['utilisateur' => $this->getUser(), 'utilisateurFavoris' => $this->utilisateurRepository->find($id)])) {
            $this->addFlash('warning', 'Ce profil est déjà dans vos favoris');
            return $this->redirectToRoute('app_home');
        }

        $profilFavoris->setUtilisateurFavoris($this->utilisateurRepository->find($id));


        $this->entityManager->persist($profilFavoris);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_favoris');
    }

    #[Route('/supprimer/favoris/{id}', name: 'app_supprimer_favoris')]
    public function supprimerFavoris($id): Response
    {
//        supprime un profil des favoris de l'utilisateur connecté
        $profilFavoris = $this->profilFavorisRepository->findOneBy(['utilisateur' => $this->getUser(), 'utilisateurFavoris' => $this->utilisateurRepository->find($id)]);
        $this->entityManager->remove($profilFavoris);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_favoris');
    }
}
