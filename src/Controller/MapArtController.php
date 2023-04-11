<?php

namespace App\Controller;

use App\Entity\MapArt;
use App\Form\MapArtType;
use App\Repository\mapRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/map/art')]
class MapArtController extends AbstractController
{
    #[Route('/', name: 'app_map_art_index', methods: ['GET'])]
    public function index(mapRepository $mapRepository): Response
    {
        return $this->render('map_art/index.html.twig', [
            'map_arts' => $mapRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_map_art_new', methods: ['GET', 'POST'])]
    public function new(Request $request, mapRepository $mapRepository): Response
    {
        $mapArt = new MapArt();
        $form = $this->createForm(MapArtType::class, $mapArt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mapRepository->save($mapArt, true);

            return $this->redirectToRoute('app_map_art_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('map_art/new.html.twig', [
            'map_art' => $mapArt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_map_art_show', methods: ['GET'])]
    public function show(MapArt $mapArt): Response
    {
        return $this->render('map_art/show.html.twig', [
            'map_art' => $mapArt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_map_art_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MapArt $mapArt, mapRepository $mapRepository): Response
    {
        $form = $this->createForm(MapArtType::class, $mapArt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mapRepository->save($mapArt, true);

            return $this->redirectToRoute('app_map_art_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('map_art/edit.html.twig', [
            'map_art' => $mapArt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_map_art_delete', methods: ['POST'])]
    public function delete(Request $request, MapArt $mapArt, mapRepository $mapRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mapArt->getId(), $request->request->get('_token'))) {
            $mapRepository->remove($mapArt, true);
        }

        return $this->redirectToRoute('app_map_art_index', [], Response::HTTP_SEE_OTHER);
    }
}
