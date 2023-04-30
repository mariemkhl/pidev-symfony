<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Entity\Events;
use App\Repository\ReservationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Knp\Component\Pager\PaginatorInterface;



class ReservationsBackController extends AbstractController
{
    #[Route('/back/reservations', name: 'app_back_reservations', methods: ['GET'])]
    public function index(Request $request, ReservationsRepository $reservationsRepository, PaginatorInterface $paginator): Response
    {
        $reservations = $reservationsRepository->findAll();
        $pagination = $paginator->paginate(
            $reservations,
            $request->query->getInt('page', 1),
            3
        ); // Numéro de page par défaut

        return $this->render('back_reservations/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    
    #[Route('/{idRes}', name: 'app_reservations_deleteback')]
    public function delete(Request $request, Reservations $reservation, ReservationsRepository $reservationsRepository): Response
    {
         $reservationsRepository->remove($reservation, true);
       

        return $this->redirectToRoute('app_back_reservations', [], Response::HTTP_SEE_OTHER);
    }

    

    
    
}
