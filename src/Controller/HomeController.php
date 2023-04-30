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
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Service\CommentaireService;

// #[Route('/article')]
class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, MyBadWordsFilter $badWordsFilter ): Response
    {
        // $articles = $articleRepository->findAll();
        // $articles = $paginator->paginate(
        //     $articleRepository->findAll(),
        //     $request->query->getInt('page', 1),
        //     6
        // );

        $entityManager = $this->getDoctrine()->getManager();

$query = $entityManager->createQuery(
    'SELECT a FROM App\Entity\Article a ORDER BY a.dateArticle DESC'
);

$articles = $query->getResult();

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
        
        return $this->render('home/home.html.twig', [
            'articles' => $articles,
        ]);
    }
}

