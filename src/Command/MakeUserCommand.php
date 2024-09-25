<?php

namespace App\Command;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Service\UserManager;
use App\Service\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:make-user',
    description: 'Créer un utilisateur (one-liner ou mode interactif)',
)]
class MakeUserCommand extends Command
{
    public function __construct(
        private UtilisateurRepository $ur,
        private EntityManagerInterface $em,
        private UserManagerInterface $userManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::OPTIONAL, 'Login')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
            ->addArgument('visibilite', InputArgument::OPTIONAL, 'Visibilité [y/n]')
            ->addArgument('admin', InputArgument::OPTIONAL, 'Est administrateur [y/n]')
            ->addArgument('code', InputArgument::OPTIONAL, 'Code');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $questionHelper = new QuestionHelper();

        // Get args
        $login      = $input->getArgument('login');
        $email      = $input->getArgument('email');
        $password   = $input->getArgument('password');
        $visibilite = $input->getArgument('visibilite');
        $admin      = $input->getArgument('admin');
        $code       = $input->getArgument('code');

        // Login check
        if ($login == null) {
            while ($login == null) {
                // Demander via mode interactif
                $login = $io->ask('Login: ', null);

                // Vérifier si n'existe pas déjà
                $user = $this->ur->findOneBy(['login' => $login]);
                if ($user != null) {
                    $io->warning("Un utilisateur existe déjà avec ce login!");
                    $login = null;
                }
            }
        } else {
            $user = $this->ur->findOneBy(['login' => $login]);
            if ($user != null) {
                $io->error("Un utilisateur existe déjà avec ce login!");
                return COMMAND::FAILURE;
            }
        }

        // Email check
        if ($email == null) {
            while ($email == null) {
                $email = $io->ask('Email: ', null);

                // Vérifier si n'existe pas déjà
                $user = $this->ur->findOneBy(['email' => $email]);
                if ($user != null) {
                    $io->warning("Un utilisateur existe déjà avec cette adresse email!");
                    $email = null;
                }
            }
        } else {
            $user = $this->ur->findOneBy(['email' => $email]);
            if ($user != null) {
                $io->error("Un utilisateur existe déjà avec cette adresse email!");
                return COMMAND::FAILURE;
            }
        }

        // Password check
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/';
        if ($password == null) {
            while ($password == null) {
                $password = $io->ask('Password: ', null);

                // Vérifier si remplit la regex: 8 caractères avec une majuscule + une minuscule + un caractère spécial
                if (!preg_match($pattern, $password)) {
                    $io->warning('Le mot de passe doit faire au moins 8 caractères, et doit comporter une majuscule, une minuscule, et un caractère spécial.');
                    $password = null;
                }
            }
        } else if (!preg_match($pattern, $password)) {
            $io->error('Le mot de passe doit faire au moins 8 caractères, et doit comporter une majuscule, une minuscule, et un caractère spécial.');
            return COMMAND::FAILURE;
        }

        // Code check
        $patternCode = "/^[a-zA-Z0-9]+$/";
        if ($code == null) {
            if ($questionHelper->ask($input, $output, new ConfirmationQuestion("Aucun code n'a été précisé, voulez-vous en définir un? (y/n) ", false))) {
                while ($code == null) {
                    $code = $io->ask('Code: ', null);
                    $user = $this->ur->findOneBy(['code' => $code]);
                    if ($user != null) {
                        $io->warning("Ce code est déjà utilisé!");
                        $code = null;
                    }
                    if (!preg_match($patternCode, $code)) {
                        $io->warning("Le code ne doit contenir que des caractères alphanumériques!");
                        $code = null;
                    }
                }
            }
        } else {
            $user = $this->ur->findOneBy(['code' => $code]);
            if ($user != null) {
                $io->error("Ce code est déjà utilisé!");
                return COMMAND::FAILURE;
            }
            if (!preg_match($patternCode, $code)) {
                $io->error("Le code ne doit contenir que des caractères alphanumériques!");
                return COMMAND::FAILURE;
            }
        }

        if ($visibilite != 'y' && $visibilite != 'n') {
            $visibilite = $questionHelper->ask($input, $output, new ConfirmationQuestion("L'utilisateur doit-il être visible? (y/n) ", false));
        } else {
            $visibilite = $visibilite == 'y';
        }

        if ($admin != 'y' && $admin != 'n') {
            $admin = $questionHelper->ask($input, $output, new ConfirmationQuestion("L'utilisateur est-il un administrateur? (y/n) ", false));
        } else {
            $admin = $admin == 'y';
        }

        // Créer user
        $user = $this->userManager->initialieUser(new Utilisateur(), $password, $email, $visibilite, $code, null);
        $user->setLogin($login);
        if($admin)
            $user->addRole('ROLE_ADMIN');

        // Persister user
        $this->em->persist($user);
        $this->em->flush();

        $code = $user->getCode();
        $io->success("L'utilisateur $login a été créé avec succès! (Code: $code)");
        return Command::SUCCESS;
    }
}
