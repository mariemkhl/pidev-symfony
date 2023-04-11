<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_article", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArticle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="titre_article", type="string", length=255, nullable=true)
     * 
     */
    private $titreArticle;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_article", type="date", nullable=true)
     */
    private $dateArticle;

    /**
     *@Assert\NotBlank(message="Le contenu ne peut pas être vide")
     * @var string
     *
     * @ORM\Column(name="content_article", type="string", length=255, nullable=false)
     */
    private $contentArticle;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbrLikes_article", type="integer", nullable=true)
     */
    private $nbrlikesArticle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_article", type="string", length=255, nullable=true)
     */
    private $imageArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="category_article", type="string", length=255, nullable=false)
     */
    private $categoryArticle;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idUser", type="integer", nullable=true)
     */
    private $iduser;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?utilisateur $idUser = null;

    #[ORM\OneToMany(mappedBy: 'idArticle', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function getTitreArticle(): ?string
    {
        return $this->titreArticle;
    }

    public function setTitreArticle(?string $titreArticle): self
    {
        $this->titreArticle = $titreArticle;

        return $this;
    }

    public function getDateArticle(): ?\DateTimeInterface
    {
        return $this->dateArticle;
    }

    public function setDateArticle(?\DateTimeInterface $dateArticle): self
    {
        $this->dateArticle = $dateArticle;

        return $this;
    }

    public function getContentArticle(): ?string
    {
        return $this->contentArticle;
    }

    public function setContentArticle(string $contentArticle): self
    {
        $this->contentArticle = $contentArticle;

        return $this;
    }

    public function getNbrlikesArticle(): ?int
    {
        return $this->nbrlikesArticle;
    }

    public function setNbrlikesArticle(?int $nbrlikesArticle): self
    {
        $this->nbrlikesArticle = $nbrlikesArticle;

        return $this;
    }

    public function getImageArticle(): ?string
    {
        return $this->imageArticle;
    }

    public function setImageArticle(?string $imageArticle): self
    {
        $this->imageArticle = $imageArticle;

        return $this;
    }

    public function getCategoryArticle(): ?string
    {
        return $this->categoryArticle;
    }

    public function setCategoryArticle(string $categoryArticle): self
    {
        $this->categoryArticle = $categoryArticle;

        return $this;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(?int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }


}
