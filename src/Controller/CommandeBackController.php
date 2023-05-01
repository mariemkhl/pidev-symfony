<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\Commande1Type;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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

#[Route('/searchcommande', name: 'searchcommande')]
public function searchCommandex(Request $request, NormalizerInterface $Normalizer, ManagerRegistry $registry)
{
    $repository = $registry->getManager()->getRepository(Commande::class);
    $requestString = $request->get('searchValue');
    $Commandes = $repository->findBy(['payment' => $requestString]);
    $jsonContent = $Normalizer->normalize($Commandes, 'json', ['groups' => 'Commande']);
    $retour = json_encode($jsonContent);
    return new Response($retour);
}




}