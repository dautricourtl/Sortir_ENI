<?php

namespace App\Controller;


use App\Entity\Event;
use App\Form\EventType;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends AbstractController
{

  #[Route('/addParticipant/{id}', name: 'add_participant', requirements: ['id' => '\d+'])]
  public function addParticipant($id, EventRepository $eventRepository, EntityManagerInterface $em): Response
  {
    $participant = $this->getUser();
    $event = $eventRepository->find($id);

    $event->addParticipant($participant);

    $em->persist($event);
    $em->flush();

    return $this->render('main/detailevent.html.twig', ['event' => $event, 'id' => $id]);
  }

  #[Route('/addEvent', name: 'app_event')]
  public function addEvent(Request $request, EntityManagerInterface $em, StateRepository $stateRepository, UserRepository $userRepository): Response
  {
    $event = new Event();
    $formbuilder = $this->createForm(EventType::class, $event);

    $owner = $this->getUser();
    $event->setOwner($userRepository->findById($owner)[0]);


    $event->setBeginAt(new \DateTime('now'));
    $event->setLimitInscriptionAt(new \DateTime('now'));

    $state = $stateRepository->findById(2)[0];
    $event->setState($state);

    $event->setIsDisplay(1);
    $event->setIsActive(true);

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
        $this->addFlash('danger', 'Format de photo jpg, png, 500Ko maximum');
        $eventForm = $formbuilder->createView();
        return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
      }
      $em->persist($event);
      $em->flush();
      $this->addFlash('success', 'La sortie a bien été enregistrée');

      return $this->redirectToRoute('main');
    }
    $eventForm = $formbuilder->createView();
    return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
  }



  #[Route('/detailEvent/{id}', name: 'event_detail', requirements: ['id' => '\d+'])]
  public function detail($id, EventRepository $eventRepository): Response
  {
    $event = $eventRepository->find($id);
    if (!$event) {
      throw new NotFoundHttpException();
    } else {
      return $this->render('main/detailevent.html.twig', ['event' => $event, 'id' => $id]);
    }
  }


  #[Route('/updateEvent/{id}', name: 'event_update', requirements: ['id' => '\d+'])]
  public function update(Request $request, $id, EntityManagerInterface $em, EventRepository $eventRepository): Response
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
      $em->persist($event);
      $em->flush();
      $this->addFlash('success', 'La sortie a bien été éditée');
      return $this->redirectToRoute('event_detail', ['event' => $event, 'id' => $id]);
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
      $state = $stateRepository->findById(1)[0];
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

  // #[Route('/publishEvent/{id}', name: 'event_publish', requirements: ['id' => '\d+'])]
  // public function publish($id, EventRepository $eventRepository, EntityManagerInterface $em): Response
  // {
  //     $event = $eventRepository->find($id);
  //     $event->getState()->setName("Ouvert");
  //     $event->setIsDisplay(0);
  //     $em->persist($event);
  //     $em->flush();
  //     $this->addFlash('success', 'La sortie est publiée');
  //     return $this->redirectToRoute('event_detail', ['event' => $event, 'id' => $id]);
  //   }
  
}
