<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackCommentaireController extends AbstractController
{
    #[Route('/back/commentaire', name: 'app_back_commentaire')]
    public function index(): Response
    {
        return $this->render('back_commentaire/index.html.twig', [
            'controller_name' => 'BackCommentaireController',
        ]);
    }
}
