<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commentaire;
use App\Form\BackCommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;

class BackCommentaireController extends AbstractController
{
   
    #[Route('/back/commentaire', name: 'app_back_commentaire', methods: ['GET', 'POST'])]

    public function index(CommentaireRepository $commentaireRepository, Request $request): Response
    {
        $commentaires = $commentaireRepository->findAll();
        $commentaire = new Commentaire();
        $form = $this->createForm(BackCommentaireType::class, $commentaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_back_index');
        }

        return $this->render('back_commentaire/index.html.twig', [
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/back/commentaire/{idCommentaire}', name: 'app_commentaire_delete', methods: ['DELETE'])]
    public function delete(Request $request, Commentaire $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdCommentaire(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_back_commentaire');
    }
    
   
    

}