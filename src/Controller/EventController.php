<?php

namespace App\Controller;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\State;
use App\Form\EventType;
use App\Entity\Location;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use App\Repository\StateRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{

  #[Route('/ajoutLocation', name:'add_location')]
  public function ajouterLocation(EntityManagerInterface $em, LocationRepository $LocationRepository) {
    $location = new Location();

    $location -> setName('Le site de test');
    $location -> setAdress("l'adresse de test");
    $location -> setLatitude("la latitude de test");
    $location -> setLongitude("la longitude de test");
    $em ->persist($location);
    $em ->flush();
    return new Response("Jeu d'essai inséré");
  }

  #[Route('/ajoutState', name:'add_state')]
  public function ajouterState(EntityManagerInterface $em) {
    $state = new State();

    $state -> setName("etat");
    $em ->persist($state);
    $em ->flush();
   return new Response("Jeu d'essai inséré");
  }

  #[Route('/ajoutOwner', name:'add_owner')]
  public function ajouterOwner(EntityManagerInterface $em, SiteRepository $siteRepository) {
    $owner = new User();
    $owner -> setPseudo("bloup");
    $owner -> setPassword("bloup");
    $owner -> setName("bloup");
    $owner -> setSurname("bloup");
    $owner -> setTel("bloup");
    $owner -> setMail("bloup");   
    $owner -> isActive(true);  
    $site = $siteRepository->findById(1)[0];
    $owner ->setSite($site);
    $em ->persist($owner);
    $em ->flush();
   return new Response("Jeu d'essai inséré");
  }

  #[Route('/addEvent', name: 'app_event')]
  public function addEvent(Request $request, EntityManagerInterface $em, StateRepository $stateRepository, SiteRepository $siteRepository, UserRepository $userRepository, LocationRepository $locationRepository) : Response {
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



}
