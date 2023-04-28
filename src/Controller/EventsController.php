<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Utilisateur;
use App\Form\EventsType;
use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;



#[Route('/events')]
class EventsController extends AbstractController
{
    #[Route('/', name: 'app_events_index', methods: ['GET'])]
    public function index(Request $request ,EventsRepository $eventsRepository, PaginatorInterface $paginator): Response
    {
        $events = $eventsRepository->findAll();
        $pagination = $paginator->paginate(
        $events, $request->query->getInt('page', 1),3); // Numéro de page par défaut
        return  $this->render('events/index.html.twig', [
           'pagination' => $pagination,
      ]);
        
    }

   

   #[Route('/new', name: 'app_events_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventsRepository $eventsRepository,ManagerRegistry $doctrine): Response
    
        {
            $event = new Events();
       
                $form = $this->createForm(EventsType::class, $event);
    
            $form->handleRequest($request);
            $entitymanager=$doctrine->getManager();
        
            if ($form->isSubmitted() && $form->isValid()) {
                $utilisateur = new Utilisateur();

                $utilisateur->setIduser(1);
$event->setIdUser($utilisateur);
              
            $imgFile = $form->get('img')->getData();
            if ($imgFile) {
                $newFilename = uniqid().'.'.$imgFile->guessExtension();
                try {
                    $imgFile->move(
                        $this->getParameter('events_images_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle file upload error
                }
                $event->setImg($newFilename);
                $entitymanager->persist($event);

                $entitymanager->flush();
            }               
                return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
    
            }
        
            return $this->renderForm('events/new.html.twig', [
            'event' => $event,
            'form' => $form,
            ]);
        }


    #[Route('/{idEvent}', name: 'app_events_show', methods: ['GET'])]
    public function show(Events $event): Response
    {
        return $this->render('events/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{idEvent}/edit', name: 'app_events_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Events $event, EventsRepository $eventsRepository): Response
    {
        $form = $this->createForm(EventsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $eventsRepository->save($event, true);

            return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('events/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{idEvent}', name: 'app_events_delete', methods: ['POST'])]
    public function delete(Request $request, Events $event, EventsRepository $eventsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getIdEvent(), $request->request->get('_token'))) {
            $eventsRepository->remove($event, true);
        }

        return $this->redirectToRoute('app_events_index', [], Response::HTTP_SEE_OTHER);
    }
   

}