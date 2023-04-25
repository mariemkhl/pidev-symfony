<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class PdfController extends AbstractController
{
    public function generate(ArticleRepository $articleRepository): Response
    {
        // Fetch an Article object from the repository
        $article = $articleRepository->findOneBy([]);

        // Create a new Dompdf instance
        $dompdf = new Dompdf();

        // Generate the HTML content for the PDF
        $html = $this->renderView('article/article_pdf.html.twig', [
            'article' => $article,
        ]);


// Modify the HTML to include the image
$html = str_replace('{{ asset(\'uploads/images/\' ~ article.imageArticle) }}', $this->getParameter('kernel.project_dir') . '/public/uploads/images/' . $article->getImageArticle(), $html);


        // Load the HTML content into the Dompdf instance
        $dompdf->loadHtml($html);

        // Set the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF file
        $dompdf->render();

        // Output the PDF file to the browser
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="article.pdf"',
        ]);
    }
}
