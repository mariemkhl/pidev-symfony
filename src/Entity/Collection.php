<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Collection
 *
 * @ORM\Table(name="collection")
 * @ORM\Entity
 */
class Collection
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_col", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCol;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_col", type="string", length=255, nullable=false)
     */
    private $nomCol;

    /**
     * @var int
     *
     * @ORM\Column(name="id_p", type="integer", nullable=false)
     */
    private $idP;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_p", type="string", length=255, nullable=false)
     */
    private $nomP;

    public function getIdCol(): ?int
    {
        return $this->idCol;
    }

    public function getNomCol(): ?string
    {
        return $this->nomCol;
    }

    public function setNomCol(string $nomCol): self
    {
        $this->nomCol = $nomCol;

        return $this;
    }

    public function getIdP(): ?int
    {
        return $this->idP;
    }

    public function setIdP(int $idP): self
    {
        $this->idP = $idP;

        return $this;
    }

    public function getNomP(): ?string
    {
        return $this->nomP;
    }

    public function setNomP(string $nomP): self
    {
        $this->nomP = $nomP;

        return $this;
    }


}
