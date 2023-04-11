<?php

namespace App\Entity;
use App\Repository\mapRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * MapArt
 *
 * @ORM\Table(name="map_art")
 * @ORM\Entity(repositoryClass="App\Repository\mapRepository")
 */
class MapArt
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
     * @var string
     *
     * @ORM\Column(name="nomplace", type="string", length=120, nullable=false)
     */
    private $nomplace;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=300, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=120, nullable=false)
     */
    private $lien;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=120, nullable=false)
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="nblikes", type="integer", nullable=false)
     */
    private $nblikes = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="Latitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="Longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=100, nullable=false)
     */
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomplace(): ?string
    {
        return $this->nomplace;
    }

    public function setNomplace(string $nomplace): self
    {
        $this->nomplace = $nomplace;

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

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getNblikes(): ?int
    {
        return $this->nblikes;
    }

    public function setNblikes(int $nblikes): self
    {
        $this->nblikes = $nblikes;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

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


}
