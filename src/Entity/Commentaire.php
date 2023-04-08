<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommentaireRepository;
/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="id_article", columns={"id_article"})})
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commentaire", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCommentaire;

    /**
     * @var int
     *
     * @ORM\Column(name="id_article", type="integer", nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\article", inversedBy="commentaires")
     * @ORM\JoinColumn(name="id_article", referencedColumnName="id_article")
     */
    private $idArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="content_commentaire", type="string", length=255, nullable=false)
     */
    private $contentCommentaire;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_commentaire", type="date", nullable=true)
     */
    private $dateCommentaire;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_likes_commentaire", type="integer", nullable=false)
     */
    private $nbLikesCommentaire;

    /**
     * @var bool
     *
     * @ORM\Column(name="etat_commentaire", type="boolean", nullable=false)
     */
    private $etatCommentaire;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\ManyToOne(targetEntity="App\Entity\utilisateur", inversedBy="commentaires")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     */
    private $idUser;

    

    public function getIdCommentaire(): ?int
    {
        return $this->idCommentaire;
    }

    public function getIdArticle(): ?int
    {
        return $this->idArticle;
    }

    public function setIdArticle(int $idArticle): self
    {
        $this->idArticle = $idArticle;

        return $this;
    }

    public function getContentCommentaire(): ?string
    {
        return $this->contentCommentaire;
    }

    public function setContentCommentaire(string $contentCommentaire): self
    {
        $this->contentCommentaire = $contentCommentaire;

        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(?\DateTimeInterface $dateCommentaire): self
    {
        $this->dateCommentaire = $dateCommentaire;

        return $this;
    }

    public function getNbLikesCommentaire(): ?int
    {
        return $this->nbLikesCommentaire;
    }

    public function setNbLikesCommentaire(int $nbLikesCommentaire): self
    {
        $this->nbLikesCommentaire = $nbLikesCommentaire;

        return $this;
    }

    public function isEtatCommentaire(): ?bool
    {
        return $this->etatCommentaire;
    }

    public function setEtatCommentaire(bool $etatCommentaire): self
    {
        $this->etatCommentaire = $etatCommentaire;

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


}
