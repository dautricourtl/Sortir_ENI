<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    #[Route('/addGroup', name: 'addGroup')]
    public function addGroup(): Response
    {
        return $this->render('main/group.html.twig', [
        ]);
    }
}
