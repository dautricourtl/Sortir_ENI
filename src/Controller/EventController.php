<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\Form\Form;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\SiteRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends AbstractController
{


  #[Route('/addParticipant/{id}', name: 'add_participant')]
  public function addParticipant(Event $event, UserInterface $participant, EventRepository $eventRepository, SiteRepository $siteRepository, EntityManagerInterface $em):Response
  {

    /** @var User $participant */
    $sites = $siteRepository->findAll();
    $events = $eventRepository->findAll();
    
    $exist = false;
    $event->setisInEvent($participant);
    
    $exist = $event->isInEvent();
    if($exist){
      $event->removeParticipant($participant);
    }else{
      if( $event->getLimitInscriptionAt()  > date("Y-m-d H:i:s")){
        $event->addParticipant($participant);
      }
    }

    $em->persist($event);
    $em->flush();  

    return $this->render('main/index.html.twig', [
      'events' =>$events,
      'isInEvent' => $event->participantExistInEvent($participant),
      'sites' =>$sites
    ]);
    
  }

  #[Route('/addEvent', name: 'app_event')]
  public function addEvent(Request $request, EntityManagerInterface $em, StateRepository $stateRepository,UserInterface $participant, UserRepository $userRepository): Response
  {
      /** @var User $participant */
    $event = new Event();
    $formbuilder = $this->createForm(EventType::class, $event);

    $owner = $this->getUser();
    $event->setOwner($userRepository->findById($owner)[0]);


    $event->setBeginAt(new \DateTime('now'));
    $event->setLimitInscriptionAt(new \DateTime('now'));

    $event->setIsDisplay(1);
    $event->setIsActive(true);

    $event->addParticipant($participant);
    $formbuilder->handleRequest($request);

    if ($formbuilder->isSubmitted() && $formbuilder->isValid()) {
      $dateDebutVerification = $event->getBeginAt();
      $dateLimiteVerification = $event->getLimitInscriptionAt();
      $durationVerification = $event->getDuration();
      $nbinscriptionVerifcation = $event->getInscriptionMax();

      if ($durationVerification < 0 || $nbinscriptionVerifcation < 0 || $dateLimiteVerification >= $dateDebutVerification) {

        if ($durationVerification < 0) {
          $this->addFlash('danger', 'La durée doit être positive');
        }
        if ($nbinscriptionVerifcation < 0) {
          $this->addFlash('danger', 'Le nombre d\'inscrits doit être positif');
        }
        if ($dateLimiteVerification >= $dateDebutVerification) {
          $this->addFlash('danger', 'La date limite d\'inscription doit être antérieure à la date de début');
        }

        $eventForm = $formbuilder->createView();
        return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
      }
      $uploadedFile = $formbuilder['imageFile']->getData();
      if ($uploadedFile) {
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images/event';
        $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move(
          $destination,
          $newFilename
        );
        $event->setPhoto($newFilename);
      } else {
        $event->setPhoto('62286ac7498f5.png');      
      }

     if($formbuilder instanceof Form) {
       if($formbuilder->getClickedButton() && 'save' === $formbuilder->getClickedButton()->getName()){
        $state = $stateRepository->findById(3)[0];
        $event->setState($state);
        $event->setIsDisplay(0);
        $em->persist($event);
        $em->flush();
        $this->addFlash('success', 'La sortie a bien été enregistrée');
        $eventId = $event->getId();
        return $this->redirectToRoute('event_detail', ['event' => $event, 'id' => $eventId]);
       } else {
        $state = $stateRepository->findById(1)[0];
        $event->setState($state);
        $event->setIsDisplay(1);
        $em->persist($event);
        $em->flush();
        $this->addFlash('success', 'La sortie a bien été publiée');
        return $this->redirectToRoute('main');
       }
     }
    }
    $eventForm = $formbuilder->createView();
    return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
  }


  #[Route('/detailEvent/{id}', name: 'event_detail', requirements: ['id' => '\d+'])]
  public function detail(UserInterface $participant, $id, EventRepository $eventRepository): Response
  {

    /** @var User $participant */

    $event = $eventRepository->find($id);
    if (!$event) {
      throw new NotFoundHttpException();
    } else {

     $event->setisInEvent($participant);   

      return $this->render('main/detailevent.html.twig', ['event' => $event, 'id' => $id]);
    }
  }


  #[Route('/updateEvent/{id}', name: 'event_update', requirements: ['id' => '\d+'])]
  public function update(Request $request, $id, EntityManagerInterface $em, EventRepository $eventRepository, StateRepository $stateRepository): Response
  {

    $event = $eventRepository->findOneById($id);
    $formbuilder = $this->createForm(EventType::class, $event);
    $formbuilder->handleRequest($request);

    if ($formbuilder->isSubmitted() && $formbuilder->isValid()) {
      $dateDebutVerification = $event->getBeginAt();
      $dateLimiteVerification = $event->getLimitInscriptionAt();
      $durationVerification = $event->getDuration();
      $nbinscriptionVerifcation = $event->getInscriptionMax();

      if ($durationVerification < 0 || $nbinscriptionVerifcation < 0 || $dateLimiteVerification >= $dateDebutVerification) {

        if ($durationVerification < 0) {
          $this->addFlash('danger', 'La durée doit être positive');
        }
        if ($nbinscriptionVerifcation < 0) {
          $this->addFlash('danger', 'Le nombre d\'inscrits doit être positif');
        }
        if ($dateLimiteVerification >= $dateDebutVerification) {
          $this->addFlash('danger', 'La date limite d\'inscription doit être antérieure à la date de début');
        }

        $eventForm = $formbuilder->createView();
        return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
      }
      $uploadedFile = $formbuilder['imageFile']->getData();
      if ($uploadedFile) {
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images/event';
        $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move(
          $destination,
          $newFilename
        );
        $event->setPhoto($newFilename);
      }

      if($formbuilder instanceof Form) {
        if($formbuilder->getClickedButton() && 'save' === $formbuilder->getClickedButton()->getName()){
         $state = $stateRepository->findById(3)[0];
         $event->setState($state);  
         $event->setIsDisplay(0);    
         $em->persist($event);
         $em->flush();
         $this->addFlash('success', 'La sortie a bien été enregistrée');
         $eventId = $event->getId();
         return $this->redirectToRoute('event_detail', ['event' => $event, 'id' => $eventId]);
        } else {
         $state = $stateRepository->findById(1)[0];
         $event->setState($state);
         $event->setIsDisplay(1);
         $em->persist($event);
         $em->flush();
         $this->addFlash('success', 'La sortie a bien été publiée');
         return $this->redirectToRoute('main');
        }
      }

    }

    $eventForm = $formbuilder->createView();
    return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
  }


  #[Route('/cancelEvent/{id}', name: 'event_cancel', requirements: ['id' => '\d+'])]
  public function cancel($id, EntityManagerInterface $em, EventRepository $eventRepository, StateRepository $stateRepository): Response
  {
    $event = $eventRepository->findOneById($id);

    $eventDate = $event->getBeginAt();

    $date = new \DateTime('now');

    if ($eventDate <= $date) {
      $this->addFlash('warning', 'La sortie est en cours et ne peut être annulée');
      return $this->redirectToRoute('event_detail', ['event' => $event, 'id' => $id]);
    } else {

      $event->setIsActive(false);
      $state = $stateRepository->findById(4)[0];
      $event->setState($state);

      $em->persist($event);
      $em->flush();
      return $this->redirectToRoute('event_detail', ['event' => $event, 'id' => $id]);
    }
  }

  #[Route('/removeEvent/{id}', name: 'event_remove', requirements: ['id' => '\d+'])]
  public function remove($id, EntityManagerInterface $em, EventRepository $eventRepository): Response
  {
    $event = $eventRepository->findOneById($id);
    $owner = $event->getOwner();
    $user = $this->getUser();
    if ($user == $owner) {
      $em->remove($event);
      $em->flush();
      $this->addFlash('success', 'La sortie est supprimée');
      return $this->redirectToRoute('main');
    } else {
      $this->addFlash('danger', "Vous n'avez pas les droits pour supprimer cette sortie");
      return $this->redirectToRoute('event_detail', ['event' => $event, 'id' => $id]);
    }
  }

 


}
