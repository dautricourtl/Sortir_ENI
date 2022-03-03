<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Site;
use App\Form\CityType;
use App\Form\SiteType;
use App\Entity\Location;
use App\Form\LocationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $city = new City();
        $formcity  = $this->createForm(CityType::class, $city);
        $loaction = new Location();
        $formlocation  = $this->createForm(LocationType::class, $loaction);
        $site = new Site();
        $formSite  = $this->createForm(SiteType::class, $site);
        
        return $this->render('main/admin.html.twig', 
        [
            'new_cityForm' => $formcity->createView(),
            'newLocationForm' =>$formlocation->createView(),
            'newformSite' =>$formSite->createView()
        ]);
    }
}
