<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Générer 100 utilisateurs avec des données basiques
        for ($i = 1; $i <= 100; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur->setLogin('utilisateur'.$i)
            ->setEmail('utilisateur'.$i.'@example.com')
            ->setCode('CODE'.$i)
            ->setVisible(true)
            ->setNumTelephone('0123456789')
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setRoles(['ROLE_USER']);
            $password = $this->passwordHasher->hashPassword($utilisateur, 'password'.$i);
            $utilisateur->setPassword($password);
            $manager->persist($utilisateur);
        }
        $manager->flush();
    }
}
