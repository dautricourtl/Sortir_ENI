<?php

namespace App\Controller;
use App\Entity\City;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\State;
use App\Form\EventType;
use App\Entity\Location;
use App\Form\ProfileType;
use PhpParser\Builder\Method;
use App\Repository\CityRepository;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends AbstractController
{

  
  #[Route('/addEvent', name: 'app_event')]
  public function addEvent(Request $request, EntityManagerInterface $em, StateRepository $stateRepository, UserRepository $userRepository, CityRepository $cityRepository) : Response {
      $event = new Event();
      $formbuilder = $this ->createForm(EventType::class, $event);

      $owner = $this->getUser();
      $event ->setOwner($userRepository->findById($owner)[0]);
  

      $event ->setBeginAt(new \DateTime('now'));
      $event ->setLimitInscriptionAt(new \DateTime('now'));

      $state = $stateRepository->findById(1)[0];
      $event ->setState($state);
      
      $event ->setIsDisplay(1);
      $event ->setIsActive(true);


      $formbuilder->handleRequest($request);
      
      if($formbuilder->isSubmitted() && $formbuilder->isValid()){
        $dateDebutVerification = $event->getBeginAt();
        $dateLimiteVerification = $event->getLimitInscriptionAt();
        $durationVerification = $event->getDuration();
        $nbinscriptionVerifcation = $event->getInscriptionMax();
        
        if($durationVerification < 0 ||$nbinscriptionVerifcation < 0||$dateLimiteVerification >= $dateDebutVerification ){

          if($durationVerification < 0) {
            $this ->addFlash('danger', 'La durée doit être positive');
          } 
          if($nbinscriptionVerifcation < 0) {
              $this ->addFlash('danger', 'Le nombre d\'inscrits doit être positif');
          }
          if($dateLimiteVerification >= $dateDebutVerification) {
            $this ->addFlash('danger', 'La date limite d\'inscription doit être antérieure à la date de début');
          }

          $eventForm = $formbuilder->createView();
          return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
      }
        $uploadedFile = $formbuilder['imageFile']->getData();  
        if($uploadedFile){
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/event';  
            $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
        $event->setPhoto($newFilename);
      }
        $em->persist($event);
        $em->flush();  
        $this ->addFlash('success', 'La sortie a bien été ajoutée');
        return $this->redirectToRoute('main');
      }
    
 
    $eventForm = $formbuilder->createView();
    return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
  }

  #[Route('/detailEvent/{id}', name: 'event_detail', requirements: ['id'=> '\d+'], methods:['GET'])]
  public function detail($id, EventRepository $eventRepository):Response {

    $event = $eventRepository->find($id);
    if(!$event){
      throw new NotFoundHttpException();
    }else {
      return $this->render('main/detailevent.html.twig', compact("id", "event"));
    }

    }


  #[Route('/updateEvent/{id}', name: 'event_update', requirements: ['id'=> '\d+'], methods:['GET'])]
    public function update(Request $request, $id, EntityManagerInterface $em, EventRepository $eventRepository): Response {
        
        $event = $eventRepository ->findOneById($id);
        $formbuilder = $this->createForm(EventType::class, $event);
        $formbuilder->handleRequest($request);

        if($formbuilder->isSubmitted() && $formbuilder->isValid()){
          $dateDebutVerification = $event->getBeginAt();
          $dateLimiteVerification = $event->getLimitInscriptionAt();
          $durationVerification = $event->getDuration();
          $nbinscriptionVerifcation = $event->getInscriptionMax();
          
          if($durationVerification < 0 ||$nbinscriptionVerifcation < 0||$dateLimiteVerification >= $dateDebutVerification ){
  
            if($durationVerification < 0) {
              $this ->addFlash('danger', 'La durée doit être positive');
            } 
            if($nbinscriptionVerifcation < 0) {
                $this ->addFlash('danger', 'Le nombre d\'inscrits doit être positif');
            }
            if($dateLimiteVerification >= $dateDebutVerification) {
              $this ->addFlash('danger', 'La date limite d\'inscription doit être antérieure à la date de début');
            }
  
            $eventForm = $formbuilder->createView();
            return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
        }
          $uploadedFile = $formbuilder['imageFile']->getData();  
          if($uploadedFile){
          $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/event';  
              $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
              $uploadedFile->move(
                  $destination,
                  $newFilename
              );
          $event->setPhoto($newFilename);
        }
          $em->persist($event);
          $em->flush();  
          $this ->addFlash('success', 'La sortie a bien été éditée');
          return $this->redirectToRoute('event_detail');
        }
      
   
      $eventForm = $formbuilder->createView();
      return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
    }
    

  }


