<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ConsultationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{


    #[Route('/pdf', name: 'app_pdf')]
    public function pdf(UserRepository $userRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('user/UserListPdf.html.twig', [
            'users' => $userRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        $pdf = $dompdf->output();

        // Send some text response
        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"'
        ]);
    }
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Request $request,UserRepository $userRepository,EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {

        $produits = $entityManager
            ->getRepository(User::class)
            ->findAll();
        $articles = $paginator->paginate(
            $produits, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            1 // Nombre de résultats par page
        );
        return $this->render('user/index.html.twig', [
            'users' => $articles,
        ]);
    }

    #[Route('/front', name: 'front', methods: ['GET'])]
    public function front(UserRepository $userRepository): Response
    {
        return $this->render('base.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/back', name: 'back', methods: ['GET'])]
    public function back(UserRepository $userRepository): Response
    {
        return $this->render('base.html.twig', [

        ]);
    }


    #[Route('/{id}/bloquer', name: 'app_user_bloquer', methods: ['GET', 'POST'])]
    public function blocke($id,FlashyNotifier $flashy)
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(User::class)->find($id);
        $res->setIsActive(0);
        $em->persist($res);
        $em->flush();
        $flashy->error('Bloquer Avec Sucess');
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}/debloquer', name: 'app_user_debloquer', methods: ['GET', 'POST'])]
    public function deblocker($id,FlashyNotifier $flashy)
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(User::class)->find($id);
        $res->setIsActive(1);
        $em->persist($res);
        $em->flush();
        $flashy->success('Debloquer Avec Sucess');
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);

    }


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager,$id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(User::class)->find($id);
        $em->remove($res);
        $em->flush();
        return $this->redirectToRoute('app_user_index');
    }



}
