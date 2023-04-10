<?php

namespace App\Controller;

use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventFrontController extends AbstractController
{
    #[Route('/event/front', name: 'app_event_front')]
    public function index(EventsRepository $repo ): Response
    {
        return $this->render('event_front/index.html.twig', [
            'Events ' => $repo ->findAll(),
            
        ]);
    }
}
