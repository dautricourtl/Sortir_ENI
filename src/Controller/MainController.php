<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\State;
use App\Repository\EventRepository;
use App\Repository\SiteRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    #[Route('/addState', name: 'main_state')]
    public function addState(EntityManagerInterface $em)
    {
        $state = new State();
        $state->setName('Ouvert');
        $em->persist($state);
        $state = new State();
        $state->setName('Fermé');
        $em->persist($state);
        $state = new State();
        $state->setName('En création');
        $em->persist($state);
        $state = new State();
        $state->setName('Annulé');
        $em->persist($state);
        $state = new State();
        $state->setName('En cours');
        $em->persist($state);
        $state = new State();
        $state->setName('Passé');
        $em->persist($state);
        $em->flush();

        $this->addFlash('success', 'Les états ont bien été enregistrés');

        return $this->redirectToRoute('main');
    }


    #[Route('/', name: 'main')]
    public function index(EventRepository $eventrepo,  EntityManagerInterface $em, EventRepository $eventRepository, StateRepository $stateRepository, SiteRepository $siteRepository): Response
    {
        $token ="";
        $events = $eventrepo ->findAll();
        $sites = $siteRepository->findAll();
        /** @var User $participant */
        $participant = $this->getUser();
        
        
        if( $participant != null){
            foreach($events as $event){
                $event->setisInEvent($participant);
            }
            if( in_array("ROLE_ADMIN",$participant->getRoles()) && ($participant->getToken() == "" || $participant->getToken() == null)){
                $token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(40/strlen($x)) )),1,40);
                $participant->setToken($token);
                $em->persist($participant);
                $em->flush();
            }else if($participant->getToken() != "" && $participant->getToken() != null){
                $token = $participant->getToken();
            }
        }
        foreach($events as $event){
        $eventId = $event->getId();
        self::gestionDate($eventId, $em, $eventRepository, $stateRepository);
    }
        // dd($events);
        return $this->render('main/index.html.twig', [
            'events' =>$events,
            'token' => $token,
            'sites' => $sites
        ]);
   
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('main/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    
    #[Route('logout', name:'logout')]
    public function logout(): Response
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route('/disconnect', name: 'disconnect')]
    public function disconnect(): Response
    {
        return $this->redirectToRoute('login');
    }


    #[Route('/listuser', name: 'list')]
    public function list(): Response
    {
        return $this->render('user/user.html.twig');
    }


    
  public function gestionDate($id, EntityManagerInterface $em, EventRepository $eventRepository, StateRepository $stateRepository) {


    $dateDuJour = new \DateTime('now');
    $event = $eventRepository->findOneById($id);
    $duree = $event->getDuration();
    $dateDebutEvent = $event->getBeginAt();


    $dateDebutEventTemp = $dateDebutEvent->format('Y-m-d H:i:s');

    $datePast = new \DateTime($dateDebutEventTemp);
    $datePast->modify('+'.$duree. ' minutes');

    $dateArchive = new \DateTime($dateDebutEventTemp);
    $dateArchive->modify('+ 1 month');
 

    $dateLimiteInscription = $event->getLimitInscriptionAt();


       $eventState = $event->getState()->getName();


    if($eventState != 'Annulé'){

        // $state = $stateRepository->findById(1)[0];
        // $event->setIsDisplay(1);
        // $event->setState($state);
        // $em->persist($event);
        // $em->flush();  


        if($dateDuJour > $dateLimiteInscription) {
            //fermé
          $state = $stateRepository->findById(2)[0];
          $event->setState($state);
        } 

        if ($dateDuJour > $dateDebutEvent and $dateDuJour < $datePast) {
            //En cours
          $state = $stateRepository->findById(5)[0];
          $event->setState($state);     
        } 

        if ($dateDuJour > $datePast and $dateDuJour < $dateArchive) {
            //passé
          $state = $stateRepository->findById(6)[0];
          $event->setState($state);
     
        } 

        if ($dateDuJour > $dateArchive ) {
           //archivé
          $event->setIsDisplay(0);
            }

            $em->persist($event);
            $em->flush();   
        }

    }

    public function getFilter($id, EntityManagerInterface $em, EventRepository $eventRepository){

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(Event::class, 'c');
            //->where('c.isActive = 1');

        $query = $qb->getQuery();

        $result = $query->getResult();

    }
   
    
}

    

