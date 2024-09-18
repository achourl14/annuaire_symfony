<?php

namespace App\Service;

use App\Entity\Utilisateur;

interface UserManagerInterface
{
    public function initialieUser(Utilisateur $user, string $password, string $email, bool $visible, string $codeUser): Utilisateur;
}