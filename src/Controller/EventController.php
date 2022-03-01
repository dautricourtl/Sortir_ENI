<?php

namespace App\Controller;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\State;
use App\Form\EventType;
use Doctrine\ORM\EntityManager;
use App\Controller\EventController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{


  #[Route('/addEvent', name: 'app_event')]
  public function addEvent(Request $request, EntityManagerInterface $em){
      $event = new Event();
      $formbuilder = $this ->createForm(EventType::class, $event);
      $event ->setState(new State('pouet'));
      $event ->getSite(new Site('Nantes'));
      $event ->getOwner(new User('bloup')); 

      $formbuilder->handleRequest($request);
      if($formbuilder->isSubmitted() && $formbuilder->isValid()){
        $em->persist($event);
        $em->flush();
        return $this->redirectToRoute('main');
      }

      $formbuilder ->handleRequest($request);

    $eventForm = $formbuilder->createView();
      return $this->render('main/event.html.twig', ['eventForm' => $eventForm]);
  }



}
