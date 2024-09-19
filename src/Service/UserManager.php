<?php

namespace App\Service;

use App\Entity\Utilisateur;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager implements UserManagerInterface
{


    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

//    faire une fonction pour crée un utilisateur


    public function manageCodeUser(Utilisateur $user, ?string $code) : void
    {
        $generatedCode = $code;
       if ($code === null) {
//           genere un code aléaatoire en te basant sur la date du jour sans les / et les - et les : du style 18092024 + l'id de l'utilisateur
           $generatedCode = date('dmY') . $user->getId();
       }
       $user->setCode($generatedCode);
    }

    public function initialieUser(Utilisateur $user, string $password, string $email, bool $visible, ?string $codeUser) : Utilisateur
    {
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $user->setVisible($visible);
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->manageCodeUser($user, $codeUser);

        // encode the plain password
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));

        return $user;
    }

    public function modifieUser($user, string $password, string $email, bool $visible, string $codeUser, $numTelephone): Utilisateur
    {
        $user->setEmail($email);
        $user->setVisible($visible);
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setNumTelephone($numTelephone);
        $this->manageCodeUser($user, $codeUser);

        if (!empty($password)) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        }

        return $user;
    }

}