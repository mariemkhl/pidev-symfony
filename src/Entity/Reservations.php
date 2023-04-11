<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reservations
 *
 * @ORM\Table(name="reservations")
 * @ORM\Entity
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

    /**
     * @var string
     *
     * @ORM\Column(name="nameEv", type="string", length=255, nullable=false)
     */
    private $nameev;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

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

    public function getNameev(): ?string
    {
        return $this->nameev;
    }

    public function setNameev(string $nameev): self
    {
        $this->nameev = $nameev;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
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
