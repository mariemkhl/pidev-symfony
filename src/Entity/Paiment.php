<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Paiment
 *
 * @ORM\Table(name="paiment")
 * @ORM\Entity
 */
class Paiment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="num_carte", type="integer", nullable=false)
     */
    private $numCarte;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_carte", type="string", length=50, nullable=false)
     */
    private $nomCarte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ex", type="date", nullable=false)
     */
    private $dateEx;

    /**
     * @var int
     *
     * @ORM\Column(name="CV_code", type="integer", nullable=false)
     */
    private $cvCode;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_tot", type="integer", nullable=false)
     */
    private $prixTot;

    /**
     * @var int|null
     *
     * @ORM\Column(name="commande", type="integer", nullable=true)
     */
    private $commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCarte(): ?int
    {
        return $this->numCarte;
    }

    public function setNumCarte(int $numCarte): self
    {
        $this->numCarte = $numCarte;

        return $this;
    }

    public function getNomCarte(): ?string
    {
        return $this->nomCarte;
    }

    public function setNomCarte(string $nomCarte): self
    {
        $this->nomCarte = $nomCarte;

        return $this;
    }

    public function getDateEx(): ?\DateTimeInterface
    {
        return $this->dateEx;
    }

    public function setDateEx(\DateTimeInterface $dateEx): self
    {
        $this->dateEx = $dateEx;

        return $this;
    }

    public function getCvCode(): ?int
    {
        return $this->cvCode;
    }

    public function setCvCode(int $cvCode): self
    {
        $this->cvCode = $cvCode;

        return $this;
    }

    public function getPrixTot(): ?int
    {
        return $this->prixTot;
    }

    public function setPrixTot(int $prixTot): self
    {
        $this->prixTot = $prixTot;

        return $this;
    }

    public function getCommande(): ?int
    {
        return $this->commande;
    }

    public function setCommande(?int $commande): self
    {
        $this->commande = $commande;

        return $this;
    }


}
