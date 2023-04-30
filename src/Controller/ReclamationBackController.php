<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\Reclamation1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recadmin')]

class ReclamationBackController extends AbstractController 
{

  #[Route('/recadmin', name: 'admin_reclamation', methods: ['GET'])]
public function adminReclamation(EntityManagerInterface $entityManager): Response
{
    $reclamations = $entityManager
        ->getRepository(Reclamation::class)
        ->findAll();

    return $this->render('admin/backreclamation.html.twig', [
        'reclamations' => $reclamations,
    ]);
}

    #[Route('/edit', name: 'admin_reclamation_edit', methods: ['GET', 'POST'])]
    public function adminedit(Request $request, reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/backreclamation.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{numero}/delete', name: 'admin_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, $numero, EntityManagerInterface $entityManager): Response
    {
        $reclamation = $entityManager->getRepository(Reclamation::class)->findOneBy(['numero' => $numero]);
    
        if (!$reclamation) {
            throw $this->createNotFoundException('No reclamation found for numero '.$numero);
        }
    
        if ($this->isCsrfTokenValid('delete'.$reclamation->getNumero(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('admin_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route("/recherche", name: "reclamation_recherche", methods: ["POST"])]
    public function recherche(Request $request): Response
    {
    $reclamation = new Reclamation();
    $form = $this->createForm(Reclamation1Type::class, $reclamation);
    
    $form->handleRequest($request);
    
    $reclamations = []; // define an empty array for reclamations
    
    if ($form->isSubmitted() && $form->isValid()) {
        $typereclamation = $reclamation->getTypereclamation();
    
        $repository = $this->entityManager->getRepository(Reclamation::class);
        $reclamations = $repository->findBy(['typereclamation' => $typereclamation]);
        var_dump($reclamations);

    }
    
    return $this->render('admin/backreclamation.html.twig', [
        'form' => $form->createView(),
        'reclamations' => $reclamations, // pass reclamations to the Twig template
    ]);
    }
    

}