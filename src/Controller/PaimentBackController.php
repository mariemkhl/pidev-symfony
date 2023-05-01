<?php

namespace App\Controller;

use App\Entity\Paiment;
use App\Form\Paiment1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/padmin')]

class PaimentBackController extends AbstractController 
{

    #[Route('/', name: 'admin_paiment_index', methods: ['GET'])]
    public function indexadmin(EntityManagerInterface $entityManager): Response
    {
        $paiments = $entityManager
            ->getRepository(paiment::class)
            ->findAll();

        return $this->render('admin/backpaiment.html.twig', [
            'paiments' => $paiments,
        ]);
    }
    #[Route('/edit', name: 'admin_paiment_edit', methods: ['GET', 'POST'])]
    public function adminedit(Request $request, paiment $paiment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Paiment1Type::class, $paiment);
        $form->handleRequest($request);
        print($paiment);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_paiment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/backpaiment.html.twig', [
            'paiment' => $paiment,
            'form' => $form,
        ]);
    }

    #[Route('/delete', name: 'admin_paiment_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->request->get('id');
        $paiment = $entityManager->getRepository(Paiment::class)->find($id);
    
        if (!$paiment) {
            throw $this->createNotFoundException('No Paiment found for id '.$id);
        }
    
        if ($this->isCsrfTokenValid('delete'.$paiment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiment);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('admin_paiment_index', [], Response::HTTP_SEE_OTHER);
    }
    

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/searchpaiment', name: 'searchpaiment')]
    public function searchpaimentx(Request $request, NormalizerInterface $Normalizer, ManagerRegistry $registry)
    {
        $repository = $registry->getManager()->getRepository(Paiment::class);
        $requestString = $request->get('searchValue');
        $Paiments = $repository->findBy(['numCarte' => $requestString]);
        $jsonContent = $Normalizer->normalize($Paiments, 'json', ['groups' => 'Paiment']);
        $retour = json_encode($jsonContent);
        return new Response($retour);
    }
    

}