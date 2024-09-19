<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UserManagerInterface
{
    public function initialieUser(Utilisateur $user, string $password, string $email, bool $visible, string $codeUser, ?UploadedFile $fichierPhotoProfil): Utilisateur;
}