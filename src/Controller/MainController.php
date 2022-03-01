<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('main/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
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
