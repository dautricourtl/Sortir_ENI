<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Controller\CityController;
use App\Controller\UserController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('main/login.html.twig');
    }

    #[Route('/profile', name: 'profile')]
    public function profile(): Response
    {
        return $this->render('main/profile.html.twig');
    }

    #[Route('/disconnect', name: 'disconnect')]
    public function disconnect(): Response
    {
        return $this->redirectToRoute('login');
    }

    #[Route('/sign', name: 'sign')]
    public function sign(): Response
    {

        $event = UserController::getInstance();
        $retour = $event ->LoadUser();

        return $this->render('main/sign.html.twig', [
            'controller_name' => $retour,
        ]);
    }

    
}
