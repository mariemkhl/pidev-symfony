<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\Reclamation1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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
    
   #[Route('/searchreclamation', name: 'searchreclamation')]
    public function searchreclamationx(Request $request, NormalizerInterface $Normalizer, ManagerRegistry $registry)
    {
        $repository = $registry->getManager()->getRepository(Reclamation::class);
        $requestString = $request->get('searchValue');
        $Reclamations = $repository->findBy(['numero' => $requestString]);
        $jsonContent = $Normalizer->normalize($Reclamations, 'json', ['groups' => 'Reclamation']);
        $retour = json_encode($jsonContent);
        return new Response($retour);
    }

}