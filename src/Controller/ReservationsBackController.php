<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaire;
use App\Repository\ReservationsRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Reservations;
use App\Form\BackReservationsType;

class BackReservationController extends AbstractController
{
   
    #[Route('/back/reservations', name: 'app_back_reservations', methods: ['GET', 'POST'])]

    public function index(ReservationsRepository $reservationsRepository, Request $request): Response
    {
        $reservation = $reservationsRepository->findAll();
        $reservation = new Reservations();
        $form = $this->createForm(BackReservationsType::class, $reservation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_reservations');
        }

        return $this->render('back_reservations/index.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

   
    public function createOrEdit(Request $request, ?int $idRes): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $idRes ? $em->getRepository(Reservations::class)->find($idRes) : new Reservations();
        $form = $this->createForm(BackReseravationsType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('app_back_reservations');
        }

        return $this->render('back_reservations/editreservations.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation,
        ]);
    }

    #[Route('/back/{idRes}/editback', name: 'app_reservations_editback', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservations $reservation,ReservationsRepository $reservationsRepository ): Response
    {
        $form = $this->createForm(BackEventsType::class, $reservation
    );
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
           
            $reservationsRepository->save($reservation, true);
    
            return $this->redirectToRoute('app_back_reservations', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('back_reservations/editback.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

     #[Route('/back/reservations/{idRes}', name: 'app_reservations_deleteback', methods: ['POST'])]
     public function deleteback(Request $request,ReservationsRepository $reservationsRepository , Reservations $reservation): Response
     {
         if ($this->isCsrfTokenValid('delete'.$reservation->getIdRes(), $request->request->get('_token'))) {
            
            $reservationsRepository->remove($reservation, true);
         }

     return $this->redirectToRoute('app_back_reservations', [], Response::HTTP_SEE_OTHER);
        
     }
    
   
    

}