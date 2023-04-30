<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table(name="likes")
 * @ORM\Entity
 */
class Likes
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
     * @ORM\Column(name="RefL", type="string", length=120, nullable=false)
     */
    private $refl;

    /**
     * @var int
     *
     * @ORM\Column(name="RefU", type="integer", nullable=false)
     */
    private $refu;

    /**
     * @var string
     *
     * @ORM\Column(name="nomU", type="string", length=50, nullable=false)
     */
    private $nomu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefl(): ?string
    {
        return $this->refl;
    }

    public function setRefl(string $refl): self
    {
        $this->refl = $refl;

        return $this;
    }

    public function getRefu(): ?int
    {
        return $this->refu;
    }

    public function setRefu(int $refu): self
    {
        $this->refu = $refu;

        return $this;
    }

    public function getNomu(): ?string
    {
        return $this->nomu;
    }

    public function setNomu(string $nomu): self
    {
        $this->nomu = $nomu;

        return $this;
    }


}
