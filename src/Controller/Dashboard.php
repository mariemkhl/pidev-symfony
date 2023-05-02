<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaire;
use App\Form\BackCommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
   
    #[Route('/dashboard', name: 'app_dashboard_commentaire', methods: ['GET', 'POST'])]
    public function index(CommentaireRepository $commentaireRepository, Request $request): Response
    {
        // Render form
        $form = $this->createForm(BackCommentaireType::class, null, ['action' => $this->generateUrl('app_dashboard_commentaire')]);
    
        return $this->render('dashboard/dashboard.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}