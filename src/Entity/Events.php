<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Events
 *
 * @ORM\Table(name="events")
  * @ORM\Entity(repositoryClass="App\Repository\EventsRepository")

 */
class Events
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_event", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="nameEv", type="string", length=255, nullable=false)
     */
    private $nameev;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_event", type="date", nullable=false)
     */
    private $dateEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=false)
     */
    private $location;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $iduser;

    

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255, nullable=false)
     */
    private $categorie;

    /**
     * @var int
     *
     * @ORM\Column(name="nbplacetotal", type="integer", nullable=false)
     */
    private $nbplacetotal;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=255, nullable=false)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $idUser = null;


    #[ORM\OneToMany(mappedBy: 'idEvent', targetEntity: Reservations::class)]
    private Collection $reservation;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

/**
     * @return Collection<int, Reservations>
     */
 public function getReservation(): Collection
    {
        return $this->reservation;
    }


    public function getIdEvent(): ?int
    {
        return $this->idEvent;
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

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): self
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;
        $this->iduser=$idUser->getIdUser();
        return $this;
    }
    

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getNbplacetotal(): ?int
    {
        return $this->nbplacetotal;
    }

    public function setNbplacetotal(int $nbplacetotal): self
    {
        $this->nbplacetotal = $nbplacetotal;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }


}
