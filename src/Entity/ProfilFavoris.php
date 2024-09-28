<?php

namespace App\Entity;

use App\Repository\ProfilFavorisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilFavorisRepository::class)]
class ProfilFavoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'profilFavoris')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'profilFavoris')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateurFavoris = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getUtilisateurFavoris(): ?Utilisateur
    {
        return $this->utilisateurFavoris;
    }

    public function setUtilisateurFavoris(?Utilisateur $utilisateurFavoris): static
    {
        $this->utilisateurFavoris = $utilisateurFavoris;

        return $this;
    }
}
