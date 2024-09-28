<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JsonController extends AbstractController
{
    public function __construct(
        private UtilisateurRepository $utilisateurRepository
    ) {}

    #[Route('/api/utilisateurs', name: 'api_get_users', methods: ['GET'])]
    public function getUsers(): JsonResponse {
        /** @var Utilisateur $loggedInUser */
        $loggedInUser = $this->getUser();

        if ($loggedInUser === null || !$loggedInUser->isAdmin()) {
            $users = $this->utilisateurRepository->findBy(['visible' => true]);
        } else {
            $users = $this->utilisateurRepository->findAll();
        }

        $jsonUsers = [];
        foreach ($users as $user) {
            $jsonUsers[] = $user->toJSON();
        }

        return new JsonResponse($jsonUsers, 200);
    }

    #[Route('/api/utilisateurs/{code}', name: 'api_get_user_info', methods: ['GET'])]
    public function getUserInfo(?Utilisateur $utilisateur): JsonResponse
    {
        if ($utilisateur === null) {
            return new JsonResponse(['message' => "Cet utilisateur n'existe pas."], 404);
        }

        /** @var Utilisateur $loggedInUser */
        $loggedInUser = $this->getUser();

        if (!$utilisateur->isVisible() && $loggedInUser === null) {
            return new JsonResponse(['message' => "Vous n'êtes pas autorisé à effectuer cette action."], 401);
        }

        if (!$utilisateur->isVisible() && !$loggedInUser->isAdmin()) {
            return new JsonResponse(['message' => "Vous n'êtes pas autorisé à effectuer cette action."], 403);
        }

        return new JsonResponse($utilisateur->toJSON(), 200);
    }
}
