<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[UniqueEntity(fields: ['login'], message: 'There is already an account with this login')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['code'], message: 'There is already an account with this code')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $login = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    private ?int $numTelephone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $visible = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $connectedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $nomPhotoProfil = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getNumTelephone(): ?int
    {
        return $this->numTelephone;
    }

    public function setNumTelephone(?int $numTelephone): static
    {
        $this->numTelephone = $numTelephone;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getConnectedAt(): ?\DateTimeImmutable
    {
        return $this->connectedAt;
    }

    public function setConnectedAt(?\DateTimeImmutable $connectedAt): static
    {
        $this->connectedAt = $connectedAt;

        return $this;
    }

    public function addRole($role) : void {
        if(!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole($role) : void {
        $index = array_search($role, $this->roles);
        //array_search renvoie soit l'index (la clé) soit false is rien n'est trouver
        //Préciser le !== false est bien nécessaire, car si le role se trouve à l'index 0, utiliser un simple if($index) ne vérifie pas le type! Et donc, si l'index retournait est 0, la condition ne passerait pas...!
        if ($index !== false) {
            unset($this->roles[$index]);
        }
    }

    public function getNomPhotoProfil(): ?string
    {
        return $this->nomPhotoProfil;
    }

    public function setNomPhotoProfil(?string $nomPhotoProfil): static
    {
        $this->nomPhotoProfil = $nomPhotoProfil;

        return $this;
    }

    public function toJSON(): array {
        return [
            "login" => $this->login,
            "is_admin" => $this->isAdmin(),
            "visible" => $this->isVisible(),
            "email" => $this->email,
            "num_telephone" => $this->numTelephone
        ];
    }

    public function isAdmin(): bool {
        return in_array('ROLE_ADMIN', $this->roles);
    }
}
