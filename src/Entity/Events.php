<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\EventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: EventsRepository::class)]


class Events
{
    #[ORM\Id]  
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idEvent= null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private $nameev;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_event", type="date", nullable=false)
     */
    private $dateEvent;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?string $location = null ;



    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $idUser = null;

    
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?string $categorie;

    #[ORM\Column]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?int $nbplacetotal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le champ est vide!")]
    private ?int $img = null ;

    #[ORM\OneToMany(mappedBy: 'idEvent', targetEntity: Reservations::class)]
    private Collection $reservation;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }


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

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;

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

    public function __toString() {
        return $this->idEvent;
    }

    /**
     * @return Collection<int, Student>
     */
   

   public function add(Reservations $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setIdEvent($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdEvent() === $this) {
                $reservation->setIdEvent(null);
            }
        }

        return $this;
    }

}