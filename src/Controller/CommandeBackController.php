<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\Commande1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comadmin')]

class CommandeBackController extends AbstractController 
{

    #[Route('/', name: 'admin_commande_index', methods: ['GET'])]
    public function indexadmin(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        return $this->render('admin/backcommande.html.twig', [
            'commandes' => $commandes,
        ]);
    }
    #[Route('/edit', name: 'admin_commande_edit', methods: ['GET', 'POST'])]
    public function adminedit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Commande1Type::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/backcommande.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

// CommandeBackController.php
#[Route('/delete', name: 'admin_commande_delete', methods: ['POST'])]
public function delete(Request $request, EntityManagerInterface $entityManager): Response
{
    $idcommande = $request->request->get('idcommande');
    $commande = $entityManager->getRepository(Commande::class)->find($idcommande);

    if (!$commande) {
        throw $this->createNotFoundException('No commande found for id '.$idcommande);
    }

    if ($this->isCsrfTokenValid('delete'.$commande->getIdcommande(), $request->request->get('_token'))) {
        $entityManager->remove($commande);
        $entityManager->flush();
    }

    return $this->redirectToRoute('admin_commande_index', [], Response::HTTP_SEE_OTHER);
}
private $entityManager;
public function __construct(EntityManagerInterface $entityManager)
{
    $this->entityManager = $entityManager;
}

#[Route("/recherche", name: "commande_recherche", methods: ["POST"])]    
public function recherche(Request $request): Response
{
    $commande = new Commande();
    $form = $this->createForm(Commande1Type::class, $commande);

    $form->handleRequest($request);

    $commandes = [];

    if ($form->isSubmitted() && $form->isValid()) {
        $payment = $commande->getPayment();

        $repository = $this->getDoctrine()->getRepository(Commande::class);
        $commandes = $repository->findBy(['payment' => $payment]);
    }

    return $this->render('admin/backcommande.html.twig', [
        'form' => $form->createView(),
        'commandes' => $commandes,
    ]);
}



}