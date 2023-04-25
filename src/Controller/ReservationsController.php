<?php

namespace App\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Reservations;
use App\Form\ReservationsType;
use App\Repository\ReservationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/reservations')]
class ReservationsController extends AbstractController
{
    #[Route('/', name: 'app_reservations_index', methods: ['GET'])]
    public function index(ReservationsRepository $reservationsRepository): Response
    {
        return $this->render('reservations/index.html.twig', [
            'reservations' => $reservationsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservations_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationsRepository $reservationsRepository): Response
    {
        $reservation = new Reservations();
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationsRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservations/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRes}', name: 'app_reservations_show', methods: ['GET'])]
    public function show(Reservations $reservation): Response
    {
        return $this->render('reservations/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{idRes}/edit', name: 'app_reservations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservations $reservation, ReservationsRepository $reservationsRepository): Response
    {
        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationsRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservations/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{idRes}', name: 'app_reservations_delete', methods: ['POST'])]
     public function delete(Request $request, Reservations $reservation, ReservationsRepository $reservationsRepository): Response
     {
         if ($this->isCsrfTokenValid('delete'.$reservation->getIdRes(), $request->request->get('_token'))) {
             $reservationsRepository->remove($reservation, true);
        }

         return $this->redirectToRoute('app_reservations_index', [], Response::HTTP_SEE_OTHER);
     }
     #[Route('/pdf/{idRes}', name: 'app_reservations_pdf', methods: ['GET'])]
     public function pdf(Request $request, $idRes, ReservationsRepository $reservationsRepository)
     {
         $entityManager = $this->getDoctrine()->getManager();
         // Create a new Dompdf instance with default options
         $pdfOptions = new Options();
         $pdfOptions->set('defaultFont', 'Arial');
         $dompdf = new Dompdf($pdfOptions);

         // Get the reclamation data to be displayed in the PDF
         // Example query to retrieve a Reclamation by its ID
         $reservations = $reservationsRepository->find($idRes);
         $res = $this->getDoctrine()->getRepository(Reservations::class)->findOneBy(['idRes' => $reservations->getIdRes()]);

         // Render the HTML template using the fetched data
         $html = $this->renderView('reservations/pdf.html.twig', ['reservations' => $reservations]);

    //     // Load the HTML content into Dompdf
         $dompdf->loadHtml($html);

    //     // (Optional) Set the paper size and orientation
         $dompdf->setPaper('A4', 'portrait');

    //     // Render the HTML as PDF
         $dompdf->render();

    //     // Generate the PDF and send it to the browser for download
         $response = new Response($dompdf->output());
         $filename = sprintf('reservations-%s.pdf', $reservations->getIdRes());
         $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
         $response->headers->set('Content-Disposition', $disposition);
         $response->headers->set('Content-Type', 'application/pdf');
         return $response;
     }
}
