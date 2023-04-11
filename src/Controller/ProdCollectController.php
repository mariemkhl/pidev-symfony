<?php

namespace App\Controller;

use App\Entity\ProdCollect;
use App\Form\ProdCollectType;
use App\Repository\ProdCollectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prod/collect')]
class ProdCollectController extends AbstractController
{
    #[Route('/', name: 'app_prod_collect_index', methods: ['GET'])]
    public function index(ProdCollectRepository $prodCollectRepository): Response
    {
        return $this->render('prod_collect/index.html.twig', [
            'prod_collects' => $prodCollectRepository->findAll(),
        ]);
    }




    


    #[Route('/new', name: 'app_prod_collect_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProdCollectRepository $prodCollectRepository): Response
    {
        $prodCollect = new ProdCollect();
        $form = $this->createForm(ProdCollectType::class, $prodCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prodCollectRepository->save($prodCollect, true);

            return $this->redirectToRoute('app_prod_collect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prod_collect/new.html.twig', [
            'prod_collect' => $prodCollect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prod_collect_show', methods: ['GET'])]
    public function show(ProdCollect $prodCollect): Response
    {
        return $this->render('prod_collect/show.html.twig', [
            'prod_collect' => $prodCollect,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prod_collect_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProdCollect $prodCollect, ProdCollectRepository $prodCollectRepository): Response
    {
        $form = $this->createForm(ProdCollectType::class, $prodCollect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prodCollectRepository->save($prodCollect, true);

            return $this->redirectToRoute('app_prod_collect_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('prod_collect/edit.html.twig', [
            'prod_collect' => $prodCollect,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prod_collect_delete', methods: ['POST'])]
    public function delete(Request $request, ProdCollect $prodCollect, ProdCollectRepository $prodCollectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prodCollect->getId(), $request->request->get('_token'))) {
            $prodCollectRepository->remove($prodCollect, true);
        }

        return $this->redirectToRoute('app_prod_collect_index', [], Response::HTTP_SEE_OTHER);
    }
}
