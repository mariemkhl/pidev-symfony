<?php

namespace App\Controller;

use App\Entity\Paiment;
use App\Form\Paiment1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paiment')]
class PaimentController extends AbstractController
{
    #[Route('/', name: 'app_paiment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $paiments = $entityManager
            ->getRepository(Paiment::class)
            ->findAll();

        return $this->render('paiment/addpayment.html.twig', [
            'paiments' => $paiments,
        ]);
    }


    #[Route('/addpayment', name: 'addpayment', methods: ['GET', 'POST'])]
    public function addpayment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paiment = new Paiment();
        $form = $this->createForm(Paiment1Type::class, $paiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiment);
            $entityManager->flush();

            return $this->redirectToRoute('conf', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiment/addpayment.html.twig', [
            'paiment' => $paiment,
            'form' => $form,
        ]);
    }

    #[Route('/conf', name: 'conf', methods: ['GET'])]
    public function conf(EntityManagerInterface $entityManager): Response
    {
        $paiments = $entityManager
            ->getRepository(Paiment::class)
            ->findAll();

        return $this->render('paiment/conf.html.twig', [
            'paiments' => $paiments,
        ]);
    }
   

    #[Route('/{Id}', name: 'app_paiment_show', methods: ['GET'])]
    public function show(Paiment $paiment): Response
    {
        return $this->render('paiment/show.html.twig', [
            'paiment' => $paiment,
        ]);
    }

    #[Route('/{Id}/edit', name: 'app_paiment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiment $paiment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Paiment1Type::class, $paiment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiment/edit.html.twig', [
            'paiment' => $paiment,
            'form' => $form,
        ]);
    }

    #[Route('/{Id}', name: 'app_paiment_delete', methods: ['POST'])]
    public function delete(Request $request, Paiment $paiment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiment_index', [], Response::HTTP_SEE_OTHER);
    }

}
