<?php

namespace App\Entity;
use App\Repository\likesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table(name="likes", indexes={@ORM\Index(name="fkuser", columns={"RefU"})})
 * @ORM\Entity(repositoryClass="App\Repository\likesRepository")
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
     * @var string
     *
     * @ORM\Column(name="nomU", type="string", length=50, nullable=false)
     */
    private $nomu;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RefU", referencedColumnName="Id_user")
     * })
     */
    #[ORM\ManyToOne(inversedBy: 'Likes')]
    
    private ?Utilisateur $refu = null;

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

    public function getNomu(): ?string
    {
        return $this->nomu;
    }

    public function setNomu(string $nomu): self
    {
        $this->nomu = $nomu;

        return $this;
    }

    public function getRefu(): ?Utilisateur
    {
        return $this->refu;
    }

    public function setRefu(?Utilisateur $refu): self
    {
        $this->refu = $refu;

        return $this;
    }
    


}
