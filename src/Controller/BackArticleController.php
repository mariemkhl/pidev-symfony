<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Form\BackArticleType;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class BackArticleController extends AbstractController
{
    #[Route('/back/article', name: 'app_back_article', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $paginator->paginate(
            $articleRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
    
        return $this->render('back_article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    

    #[Route('/back/{idArticle}/editback', name: 'app_article_editback', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(BackArticleType::class, $article);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageArticle')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }
    
                $article->setImageArticle($newFilename);
            }
    
            $articleRepository->save($article, true);
    
            return $this->redirectToRoute('app_back_article', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('back_article/editback.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/back/newback', name: 'app_article_newback', methods: ['GET', 'POST'])]
public function new(Request $request, ArticleRepository $articleRepository): Response
{
    $article = new Article();
    $form = $this->createForm(BackArticleType::class, $article);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageArticle')->getData();
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();

            try {
                $imageFile->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // handle exception if something happens during file upload
            }

            $article->setImageArticle($newFilename);
        }

        $articleRepository->save($article, true);

        return $this->redirectToRoute('app_back_article', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('back_article/newback.html.twig', [
        'article' => $article,
        'form' => $form,
    ]);
}

    
}


