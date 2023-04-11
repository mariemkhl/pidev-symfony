<?php

namespace App\Entity;

use App\Repository\ProdCollectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdCollectRepository::class)]
class ProdCollect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $Prod_id = null;

    #[ORM\Column(length: 255)]
    private ?string $Prod_nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'PRODcol')]
    private Collection $products;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->productsNames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getProdId(): ?int
    {
        return $this->Prod_id;
    }

    public function setProdId(int $Prod_id): self
    {
        $this->Prod_id = $Prod_id;

        return $this;
    }

    public function getProdNom(): ?string
    {
        return $this->Prod_nom;
    }

    public function setProdNom(string $Prod_nom): self
    {
        $this->Prod_nom = $Prod_nom;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addPRODcol($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removePRODcol($this);
        }

        return $this;
    }

   
}
