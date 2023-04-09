<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ReservationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]

class Reservations
{
    #[ORM\Id]  
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idRes = null;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    private ?Events $idEvent = null;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $idUser = null;
    

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?string $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRE", type="date", nullable=false)
     */
    private $datere;



    public function getIdRes(): ?int
    {
        return $this->idRes;
    }

  public function getIdEvent(): ?Events
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Events $idEvent): self
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDatere(): ?\DateTimeInterface
    {
        return $this->datere;
    }

    public function setDatere(\DateTimeInterface $datere): self
    {
        $this->datere = $datere;

        return $this;
    }


}
