<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends AbstractController
{
    #[Route('/newsite', name: 'new_site')]
    public function newSite(Request $request, EntityManagerInterface $em): Response
    {

        $site = new Site();
        $form  = $this->createForm(SiteType::Class, $site);
        $siteForm = $form->createView();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) 
        {
          
          $em = $this->getDoctrine()->getManager();
          
          $em->persist($site);
          $em->flush();
        }


        return $this->render('main/newSite.html.twig', [
            'newSite' => $siteForm,
        ]);
    }
}
