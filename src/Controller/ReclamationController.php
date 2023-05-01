<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\Reclamation1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Rest\Client;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/addreclamation.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/admin', name: 'admin_reclamation_index', methods: ['GET'])]
    public function indexadmin(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('admin/backreclamation.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/addreclamation', name: 'addreclamation', methods: ['GET', 'POST'])]
    public function addreclamation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('confirmation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/addreclamation.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    #[Route('/conf', name: 'confirmation', methods: ['GET', 'POST'])]
    public function conf(Request $request): Response
    {
            // Check if the form has been submitted and is valid
            if ($request->isMethod('POST') && $request->request->get('user')) {
                // Create a new instance of the Twilio client
                $accountSid = 'ACe8e0b1a487f1f33d73e1e603879ed810';
                $authToken = '24df17038f305caa769635881ce2bc35';
                $client = new Client($accountSid, $authToken);
    
                // Send an SMS message
                $message = $client->messages->create(
                    '+21654057529', // replace with admin's phone number
                    [
                        'from' => '+15672922795', // replace with your Twilio phone number
                        'body' => 'Votre réclamation a été bien ajoutée. Merci pour votre patiente. L"équipe artisty! ' . $request->request->get('user'),
                    ]
                );
    
                // Return a JSON response
                return $this->json([
                    'message' => 'SMS sent successfully!'
                ]);
            }
    
            // Render the form
            return $this->render('reclamation/confirmation.html.twig');
        }

    
}   
