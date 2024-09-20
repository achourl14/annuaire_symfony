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
        #[Autowire('%dossier_photo_profils%')] private string $dossier_photo_profils
    ) {}

    #[Route('/', name: 'app_home')]
    public function index(UtilisateurRepository $ur): Response
    {
        $users = $ur->findAll();
        return $this->render('home/index.html.twig', [
            'utilisateurs' => $users,
            //'dossierPP' => $this->dossier_photo_profils
            'dossierPP' => 'img/utilisateurs/uploads'
         ]);
    }
}
