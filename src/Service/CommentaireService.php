<?php

namespace App\Service;

use App\Entity\Article;
use App\Entity\Commentaire;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CommentaireService
{
    private $manager;
    private $flash;

    public function __construct(EntityManagerInterface $manager, FlashBagInterface $flash)
    {
        $this->manager = $manager;
        $this->flash = $flash;
    }

    public function persistCommentaire(Commentaire $commentaire, Article $article = null): void
    {
        $commentaire->setIsPublished(false)
            ->setArticle($article)
            ->setDateCommentaire(new DateTime('now'));
            
        $this->manager->persist($commentaire);
        $this->manager->flush();
        $this->flash->add('success', 'Votre commentaire est bien envoyé, merci. Il sera publié après validation.');
    }
}
