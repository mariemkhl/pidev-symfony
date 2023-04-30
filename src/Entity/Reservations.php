<?php

namespace App\Entity;
use App\Entity\Events;
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

  

    /**
     * @var int
     *
     * @ORM\Column(name="id_event", type="integer",  nullable=false)
     */
    private $idEvent=0;


    /**
     * @var int
     *
     * @ORM\Column(name="Id_user", type="integer",  nullable=false)
     */
    private $idUser=0;


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

    
    public function getIdEvent(): ?int
     {
         return $this->idEvent;
     }
 
     public function setIdEvent(?int $idEvent): self
     {
         $this->idEvent = $idEvent;
 
         return $this;
     }


    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): self
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