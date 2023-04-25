<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Security;
use App\Service\FacebookService;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Dompdf\Dompdf;
use Dompdf\Options;
use Mpdf\Mpdf;
use App\Service\MyBadWordsFilter;



#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, MyBadWordsFilter $badWordsFilter): Response
    {
        $articles = $articleRepository->findAll();
        
        foreach ($articles as $article) {
            //Filter the subject artical BAD WORDs :
            $titreArticle = $article->getTitreArticle();
            $filteredTitle = $badWordsFilter->filter($titreArticle);
            $article->setTitreArticle($filteredTitle);
    
            //Filter the content artical BAD WORDs :
            $contentArticle = $article->getContentArticle();
            $filteredContent = $badWordsFilter->filter($contentArticle);
            $article->setContentArticle($filteredContent);
        }
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    
    

    #[Route('/searcharticle', name: 'searcharticle')]
    public function searcharticle(Request $request, ArticleRepository $articleRepository, SerializerInterface $serializer): JsonResponse
    {
        $requestString = $request->get('q');
        $articles = $articleRepository->findArticleByNom($requestString);
    
        $jsonContent = $serializer->serialize($articles, 'json', ['groups' => 'Article']);
        return new JsonResponse($jsonContent, 200, [], true);
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


#[Route('/listp', name: 'article_list', methods: ['GET'])]
    public function listp(EntityManagerInterface $entityManager): Response
    {
        $pdfOptions = new Options();
         
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $articles = $entityManager
            ->getRepository(Article::class)
            ->findAll();
         
        $html = $this->renderView('article/listp.html.twig', [
            'articles' => $articles,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    
        return new Response(); // Ajoutez cette ligne pour éviter l'erreur
    }

//   /**
//       * @Route("/pdf", name="PDF_Reclamation", methods={"GET"})
//       */
//       public function pdf(ArticleRepository $articleRepository)
//       {
//           // Configure Dompdf according to your needs
//           $pdfOptions = new Options();
//           $pdfOptions->set('defaultFont', 'Arial');
  
//           // Instantiate Dompdf with our options
//           $dompdf = new Dompdf($pdfOptions);
//           // Retrieve the HTML generated in our twig file
//           $html = $this->renderView('article/pdf.html.twig', [
//               'articles' =>  $ArticleRepository->findAll(),
//           ]);
  
//           // Load HTML to Dompdf
//           $dompdf->loadHtml($html);
//           // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
//           $dompdf->setPaper('A4', 'portrait');
  
//           // Render the HTML as PDF
//           $dompdf->render();
//           // Output the generated PDF to Browser (inline view)
//           $dompdf->stream("ListeDesreclmations.pdf", [
//               "articles" => true
//           ]);
//       }
 



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

 
#[Route('/article/findByCategory', name: 'app_article_category', methods: ['GET'])]
public function findByCategory(Request $request, ArticleRepository $articleRepository): Response
{
    $categoryArticle = $request->query->get('categoryArticle');

    if ($categoryArticle) {
        $articles = $articleRepository->findBy(['categoryArticle' => $categoryArticle]);
    } else {
        $articles = $articleRepository->findAll();
    }

    return $this->render('article/index.html.twig', [
        'articles' => $articles,
    ]);
}
/**
 * @Route("/article/{id}/share-on-facebook", name="app_share_on_facebook")
 */
public function shareOnFacebook(FacebookService $facebookService, Article $article)
{
    $message = $article->getTitreArticle() . "\n" . $article->getContentArticle();
    $postId = $facebookService->postArticle($article->getUrl(), $message);

    return $this->redirectToRoute('app_article_index');
}
     



// public function postArticleOnFacebook(FacebookService $facebookService)
// {
//     // $articleUrl = 'https://example.com/article';
//     $message = $p->getTitreArticle() . "\n" . $p->getContentArticle();
//     $postId = $facebookService->postArticle( $message);
//      return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
// }





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
