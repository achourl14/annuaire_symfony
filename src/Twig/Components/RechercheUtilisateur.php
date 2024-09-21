<?php

namespace App\Twig\Components;

use App\Repository\UtilisateurRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('RechercheUtilisateur')]
final class RechercheUtilisateur
{
    use DefaultActionTrait;

    public string $query = '';

    public function __construct(
        private readonly UtilisateurRepository $utilisateurRepository
    ) {}

    public function getData(): array
    {
        return $this->utilisateurRepository->findBySimilarLogin($this->query);
    }
}
