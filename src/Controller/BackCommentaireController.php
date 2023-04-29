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
        $entityManager = $this->getDoctrine()->getManager();
    
        // Handle form submission
        if ($request->isMethod('POST')) {
            foreach ($commentaires as $commentaire) {
                $id = $commentaire->getIdCommentaire();
                $approved = $request->request->get("commentaire[$id][etatCommentaire]", false);
                $commentaire->setEtatCommentaire($approved);
                $entityManager->persist($commentaire);
            }
    
            $entityManager->flush();
            return $this->redirectToRoute('app_back_commentaire');
        }
    
        // Render form
        $form = $this->createForm(BackCommentaireType::class, null, ['action' => $this->generateUrl('app_back_commentaire')]);
    
        return $this->render('back_commentaire/index.html.twig', [
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }
    


    // public function index(CommentaireRepository $commentaireRepository, Request $request): Response
    // {
    //     $commentaires = $commentaireRepository->findAll();
    //     $commentaire = new Commentaire();
    //     $form = $this->createForm(BackCommentaireType::class, $commentaire);

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $commentaire = $form->getData();
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($commentaire);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_back_commentaire');
    //     }

    //     return $this->render('back_commentaire/index.html.twig', [
    //         'commentaires' => $commentaires,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/back/commentaires/{idCommentaire}", name="app_editback_commentaire", defaults={"idCommentaire": null})
     */
    public function createOrEdit(Request $request, ?int $idCommentaire): Response
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $idCommentaire ? $em->getRepository(Commentaire::class)->find($idCommentaire) : new Commentaire();
        $form = $this->createForm(BackCommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('app_back_commentaire');
        }

        return $this->render('back_commentaire/editcomment.html.twig', [
            'form' => $form->createView(),
            'commentaire' => $commentaire,
            
        ]);
    }

     #[Route('/back/commentaire/{idCommentaire}', name: 'app_commentaire_deleteback', methods: ['POST'])]
     public function deleteback(Request $request, Commentaire $commentaire,CommentaireRepository $commentaireRepository): Response
     {
         if ($this->isCsrfTokenValid('delete'.$commentaire->getIdCommentaire(), $request->request->get('_token'))) {
            
             $commentaireRepository->remove($commentaire, true);
         }

     return $this->redirectToRoute('app_back_commentaire', [], Response::HTTP_SEE_OTHER);
        
     }
    
   
    

}
