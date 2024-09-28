<?php

namespace App\Service;

use App\Entity\Utilisateur;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager implements UserManagerInterface
{


    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        #[Autowire('%dossier_photo_profils%')] private string $dossier_photo_profils
    )
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

    private function sauvegarderPhotoProfil(Utilisateur $utilisateur, ?UploadedFile $fichierPhotoProfil) : void {
        if($fichierPhotoProfil != null) {
            //On configure le nom de l'image à sauvegarder
            //On la déplace vers son dossier de destination
            //On met à jour l'attribut "nomPhotoProfil" de l'utilisateur
            $saveName = md5(uniqid()) . '.' . $fichierPhotoProfil->guessExtension();
            $fichierPhotoProfil->move($this->dossier_photo_profils, $saveName);
            $utilisateur->setNomPhotoProfil($saveName);
        }
    }

    public function initialieUser(Utilisateur $user, string $password, string $email, bool $visible, ?string $codeUser, ?UploadedFile $fichierPhotoProfil) : Utilisateur
    {
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $user->setVisible($visible);
        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->sauvegarderPhotoProfil($user, $fichierPhotoProfil);
        $this->manageCodeUser($user, $codeUser);

        // encode the plain password
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));

        return $user;
    }

    public function modifieUser($user, string $password, string $email, bool $visible, string $codeUser, $numTelephone,?UploadedFile $fichierPhotoProfil): Utilisateur
    {
        $user->setEmail($email);
        $user->setVisible($visible);
        $user->setUpdatedAt(new \DateTimeImmutable());
        $user->setNumTelephone($numTelephone);
        $this->sauvegarderPhotoProfil($user, $fichierPhotoProfil);
        $this->manageCodeUser($user, $codeUser);

        if (!empty($password)) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        }

        return $user;
    }

}
