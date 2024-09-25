<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly UtilisateurRepository $utilisateurRepository
    ) {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $users = $this->utilisateurRepository->findAll();
        return $this->render('home/index.html.twig', [
            'utilisateurs' => $users,
            //'dossierPP' => $this->dossier_photo_profils
            'dossierPP' => 'img/utilisateurs/uploads'
         ]);
    }
}
