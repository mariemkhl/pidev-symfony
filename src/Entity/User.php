<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['Email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id_user")]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true,name:"Email")]
    private ?string $Email = null;



    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(name:"Password")]
    private ?string $Password = null;

    #[ORM\Column(length: 255,name:"Username")]
    private ?string $Nom = null;



    #[ORM\Column(length: 255)]
    private ?string $domaine = null;



    #[ORM\Column]
    private ?bool $isActive= null;


    #[ORM\Column(name:"Num_Tel")]
    private ?int $NumTel = null;


    #[ORM\Column(length: 255,name:"Adresse")]
    private ?string $Adresse = null;

    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->Email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->Email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->Password;
    }

    public function setPassword(string $password): self
    {
        $this->Password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return int|null
     */
    public function getIdUser(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id_user
     */
    public function setIdUser(?int $id_user): void
    {
        $this->id = $id_user;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->Email;
    }

    /**
     * @param string|null $Email
     */
    public function setEmail(?string $Email): void
    {
        $this->Email = $Email;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->Nom;
    }

    /**
     * @param string|null $Nom
     */
    public function setNom(?string $Nom): void
    {
        $this->Nom = $Nom;
    }

    /**
     * @return string|null
     */
    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    /**
     * @param string|null $domaine
     */
    public function setDomaine(?string $domaine): void
    {
        $this->domaine = $domaine;
    }

    /**
     * @return int|null
     */
    public function getNumTel(): ?int
    {
        return $this->NumTel;
    }

    /**
     * @param int|null $NumTel
     */
    public function setNumTel(?int $NumTel): void
    {
        $this->NumTel = $NumTel;
    }

    /**
     * @return string|null
     */
    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    /**
     * @param string|null $Adresse
     */
    public function setAdresse(?string $Adresse): void
    {
        $this->Adresse = $Adresse;
    }












    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }




}
