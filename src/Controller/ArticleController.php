<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Security;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
        
    }



    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleRepository $articleRepository,Security $security): Response
    {
        $article = new Article();
        $iduser = $security->getUser();
        $article->setIdUser($iduser);

        $form = $this->createForm(ArticleType::class, $article);
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
    
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
    

    #[Route('/{idArticle}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
    

    #[Route('/{idArticle}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
{
    $form = $this->createForm(ArticleType::class, $article);
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

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('article/edit.html.twig', [
        'article' => $article,
        'form' => $form,
    ]);
}


    #[Route('/{idArticle}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getIdArticle(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
 * @Route("/article/search", name="app_article_search", methods={"GET"})
 */


public function search(Request $request, ArticleRepository $articleRepository): Response
{
    $searchTerm = $request->query->get('q');

    if ($searchTerm) {
        $articles = $articleRepository->findBy(['titreArticle' => $searchTerm]);
    } else {
        $articles = $articleRepository->findAll();
    }

    return $this->render('article/index.html.twig', [
        'articles' => $articles,
    ]);
}



//  public function search(Request $request, ArticleRepository $articleRepository): Response
// {
//     $searchtitreArticle = $request->query->get('q');

//     if ($searchtitreArticle) {
//         $articles = $articleRepository->searchBytitreArticle($searchtitreArticle);
//     } else {
//         $articles = $articleRepository->findAll();
//     }

//     return $this->render('article/index.html.twig', [
//         'articles' => $articles,
//     ]);
// }


  


}
