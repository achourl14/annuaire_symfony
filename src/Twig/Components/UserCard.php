<?php

namespace App\Twig\Components;
use App\Repository\UtilisateurRepository;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'Components/UserCard.html.twig')]
class UserCard
{
    use DefaultActionTrait;

    public string $dossierPP;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(
        private UtilisateurRepository $ur
    ) {}

    public function getUtilisateurs(): array
    {
        return $this->ur->findBySimilarLogin($this->query);
    }
}
