<?php

namespace App\Twig\Components;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'Components/UserCard.html.twig')]
class UserCard
{
    //public Utilisateur $user;
    public string $dossierPP;

    public function __construct(
        private UtilisateurRepository $ur
    ) {}

    public function getUtilisateurs(): array
    {
        return $this->ur->findAll();
    }
}
