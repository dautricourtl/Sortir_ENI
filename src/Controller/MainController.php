<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\EventRepository;
use App\Form\CityType;
use App\Controller\CityController;
use App\Controller\UserController;
use App\Entity\State;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function index(EventRepository $eventrepo): Response
    {
        $events = $eventrepo ->findAll();
        // dd($events);
        return $this->render('main/index.html.twig', [
            'events' =>$events,
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
    
}
