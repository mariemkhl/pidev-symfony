<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="idcommande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcommande;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_tot", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixTot;

    /**
     * @var string
     *
     * @ORM\Column(name="userid", type="string", length=255, nullable=false)
     */
    private $userid;

    /**
     * @var string
     *
     * @ORM\Column(name="payment", type="string", length=255, nullable=false)
     */
    private $payment;

    /**
     * @var string
     *
     * @ORM\Column(name="date_creation", type="string", length=255, nullable=false)
     */
    private $dateCreation;

    public function getIdcommande(): ?int
    {
        return $this->idcommande;
    }

    public function getPrixTot(): ?float
    {
        return $this->prixTot;
    }

    public function setPrixTot(float $prixTot): self
    {
        $this->prixTot = $prixTot;

        return $this;
    }

    public function getUserid(): ?string
    {
        return $this->userid;
    }

    public function setUserid(string $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(string $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(string $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }


}
