<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reservations
 *
 * @ORM\Table(name="reservations")
 * @ORM\Entity(repositoryClass="App\Repository\ReservationsRepository")

 */
class Reservations
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_res", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRes;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    private ?Events $idEvent = null;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $idUser = null;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

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
