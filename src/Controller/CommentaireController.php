<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET', 'POST'])]
    public function index(CommentaireRepository $commentaireRepository, Request $request): Response
    {
        $commentaires = $commentaireRepository->findAll();
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaires,
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/new/{idArticle}/reply', name: 'app_commentaire_reply', methods: ['GET', 'POST'])]
    // public function reply(Request $request, CommentaireRepository $commentaireRepository, int $idArticle): Response
    // {
    //     $commentaire = new Commentaire();
    //     $commentaire->setIdArticle($idArticle); // set the idArticle
    //     $form = $this->createForm(CommentaireType::class, $commentaire);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $commentaireRepository->save($commentaire, true);
    
    //         return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    //     }
    
    //     return $this->renderForm('commentaire/new.html.twig', [
    //         'commentaire' => $commentaire,
    //         'form' => $form,
    //     ]);
    // }
    
   #[Route('/reply/{idCommentaire}', name: 'app_commentaire_reply', methods: ['GET', 'POST'])]
public function reply(Request $request, CommentaireRepository $commentaireRepository, int $idCommentaire): Response
{
    // Fetch the parent comment
    $parentComment = $commentaireRepository->find($idCommentaire);
    
    // Create a new comment as a reply to the parent comment
    $commentaire = new Commentaire();
    $commentaire->setParentComment($parentComment); // set the parent comment
    $commentaire->setIdArticle($parentComment->getIdArticle()); // set the idArticle
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $commentaireRepository->save($commentaire, true);

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('commentaire/new.html.twig', [
        'commentaire' => $commentaire,
        'form' => $form,
    ]);
}

    #[Route('/new/{idArticle}', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Article $article): Response
{
    $commentaire = new Commentaire();
    $commentaire->setArticle($article);
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $commentaire->setEtatCommentaire(0);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
    }

    return $this->render('commentaire/new.html.twig', [
        'article' => $article,
        'commentaire' => $commentaire,
        'form' => $form->createView(),
    ]);
}


//     public function new(Request $request, CommentaireRepository $commentaireRepository, int $idArticle)
// {
//     // Retrieve the article from the database
//     // $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
//     $article = $this->getDoctrine()->getRepository(Article::class)->find($idArticle);


//     // Create a new Comment object and set its properties
//     $comment = new Comment();
//     $comment->setArticle($article);
//     $comment->setEtatCommentaire(true);

//     // Create a CommentForm object and handle the form submission
//     $form = $this->createForm(CommentFormType::class, $comment);
//     $form->handleRequest($request);

//     // If the form is submitted and valid, save the comment to the database
//     if ($form->isSubmitted() && $form->isValid()) {
//         $entityManager = $this->getDoctrine()->getManager();
//         $entityManager->persist($comment);
//         $entityManager->flush();

//         // Redirect to the article show page with the new comment added
//         return $this->redirectToRoute('app_commentaire_index', ['id' => $article->getId()]);
//     }

//     return $this->render('article/app_article_show', [
//         'form' => $form->createView(),
//         'article' => $article,
//     ]);
    
// }

    // public function new(Request $request, CommentaireRepository $commentaireRepository, int $idArticle): Response
    // {
    //     $commentaire = new Commentaire();
    //     $commentaire->setIdArticle($idArticle); // set the idArticle
    //     $form = $this->createForm(CommentaireType::class, $commentaire);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $commentaireRepository->save($commentaire, true);
    
    //         return $this->redirectToRoute('app_article_show', [], Response::HTTP_SEE_OTHER);
    //     }
    
    //     return $this->renderForm('commentaire/new.html.twig', [
    //         'commentaire' => $commentaire,
    //         'form' => $form,
    //     ]);
    // }
    

    #[Route('/{idCommentaire}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{idCommentaire}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{idCommentaire}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdCommentaire(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);

        
    }
}
