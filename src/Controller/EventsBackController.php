<?php

namespace App\Controller;

use App\Entity\Events;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\EventsType;
use App\Repository\EventsRepository;

use App\Form\BackEventsType;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class EventsBackController extends AbstractController
{
    #[Route('/back/events', name: 'app_back_events', methods: ['GET'])]
    public function index(Request $request ,EventsRepository $eventsRepository, PaginatorInterface $paginator): Response
    {
        $events = $eventsRepository->findAll();
        $pagination = $paginator->paginate(
        $events, $request->query->getInt('page', 1),3); // Numéro de page par défaut
        
        return $this->render('back_events/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }


    #[Route('/back/newback', name: 'app_events_newback', methods: ['GET', 'POST'])]
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
    return $this->redirectToRoute('app_back_events', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('back_events/newback.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    

    #[Route('/back/{idEvent}/editback', name: 'app_events_editback', methods: ['GET', 'POST'])]
    public function edit(Request $request, Events $event, EventsRepository $eventsRepository): Response
    {
        $form = $this->createForm(BackEventsType::class, $event);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            
           
            $eventsRepository->save($event, true);
    
            return $this->redirectToRoute('app_back_events', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('back_events/editback.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/searchEvenement', name: 'searchEvenement')]
    public function searchEvenementx(Request $request, NormalizerInterface $Normalizer, EventsRepository $sr)
    {
        $repository = $this->getDoctrine()->getRepository(Events::class);
        $requestString = $request->get('searchValue');
        $Evenements = $sr->findEventsByNom($requestString);
        $jsonContent = $Normalizer->normalize($Evenements, 'json', ['groups' => 'Evenement']);
        $retour = json_encode($jsonContent);
        return new Response($retour);
    } 

    
}

