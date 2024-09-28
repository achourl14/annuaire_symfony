<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UserManagerInterface
{
    public function modifieUser($user, string $password, string $email, bool $visible, string $codeUser,string $numTelephone, ?UploadedFile $fichierPhotoProfilupda): Utilisateur;
    public function initialieUser(Utilisateur $user, string $password, string $email, bool $visible, string $codeUser, ?UploadedFile $fichierPhotoProfil): Utilisateur;

    public function manageCodeUser(Utilisateur $user, ?string $code) : void;

    public function sauvegarderPhotoProfil(Utilisateur $utilisateur, ?UploadedFile $fichierPhotoProfil) : void;
}
