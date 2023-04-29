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
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



class BackArticleController extends AbstractController
{
    #[Route('/back/article', name: 'app_back_article', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {


        $entityManager = $this->getDoctrine()->getManager();

$query = $entityManager->createQuery(
    'SELECT a FROM App\Entity\Article a ORDER BY a.dateArticle ASC'
);

$articles = $query->getResult();

        
        $entityManager = $this->getDoctrine()->getManager();
        $now = new \DateTime();
        // Get the start of the current week (Sunday)
        $weekStart = clone $now;
        $weekStart->modify('last sunday');
    
        // Get the start of today
        $todayStart = clone $now;
        $todayStart->setTime(0, 0, 0);
    
        // Get the end of today
        $todayEnd = clone $now;
        $todayEnd->setTime(23, 59, 59);
    
        $countUsers = $entityManager->createQuery("SELECT COUNT(DISTINCT c.iduser) FROM App\Entity\Article c")->getSingleScalarResult();
        $weekCount = $entityManager->createQuery("SELECT COUNT(a.idArticle) FROM App\Entity\Article a WHERE a.dateArticle >= :weekStart")
                      ->setParameter('weekStart', $weekStart->format('Y-m-d H:i:s'))
                      ->getSingleScalarResult();
        $todayCount = $entityManager->createQuery("SELECT COUNT(a.idArticle) FROM App\Entity\Article a WHERE a.dateArticle BETWEEN :todayStart AND :todayEnd")
                      ->setParameter('todayStart', $todayStart->format('Y-m-d H:i:s'))
                      ->setParameter('todayEnd', $todayEnd->format('Y-m-d H:i:s'))
                      ->getSingleScalarResult();
    
        $counts = [
            
            'numArticles' => count($articles),
            'countUsers' => $countUsers,
            'weekCount' => $weekCount,
            'todayCount' => $todayCount,
        ];
        $articles = $articleRepository->findAll();
        $articles = $paginator->paginate(
            $articleRepository->findAll(),
            $request->query->getInt('page', 1),
            6
        );
    
      
        
        return $this->render('back_article/index.html.twig', [
            'articles' => $articles,
            'counts' => $counts,
            
        ]);
    }
    

 
    #[Route('/searchEvenement', name: 'searchEvenement')]
public function searchEvenementx(Request $request, NormalizerInterface $Normalizer, EvenementRepository $sr)
{
    $repository = $this->getDoctrine()->getRepository(Evenement::class);
    $requestString = $request->get('searchValue');
    $Evenements = $repository->findEvenementByNom($requestString);
    $jsonContent = $Normalizer->normalize($Evenements, 'json', ['groups' => 'Evenement']);
    $retour = json_encode($jsonContent);
    return new Response($retour);
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
    
        return $this->renderForm('back_article/test.html.twig', [
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


