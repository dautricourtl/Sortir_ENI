<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LocationController extends AbstractController
{
    #[Route('/newLocation', name: 'newLocation')]
    public function newLocation(Request $request, EntityManagerInterface $em): Response
    {

        $location = new Location();
        $form  = $this->createForm(LocationType::class, $location);
        $locationForm = $form->createView();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) 
        {
          
          
          $em->persist($location);
          $em->flush();
        }


        return $this->render('main/newLocation.html.twig', [
            'form_location' => $locationForm,
        ]);
    }
}
