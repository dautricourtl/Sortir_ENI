<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Controller\CityController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CityController extends AbstractController
{
  
  #[Route('/newCity', name: 'newCity')]
    public function newCity(Request $request): Response
    {

        $city = new City();
        $form  = $this->createForm(CityType::Class, $city);
        $cityForm = $form->createView();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
          $entityManager = $this->getDoctrine()->getManager();
          dd($city);
          // $entityManager->persist($city);
          // $entityManager->flush();
        }

        return $this->render('main/newCity.html.twig', [
            'form_city' => $cityForm,
        ]);
    }
   

}
