<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Site;
use App\Entity\User;
use App\Entity\State;
use App\Entity\Location;
use App\Repository\CityRepository;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use App\Repository\StateRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/api", name="api_")
 */
class APIController extends AbstractController
{
    #[Route('/getCity', name: 'getCity')]
    public function getCity(CityRepository $cityRepository, EntityManagerInterface $em){
        

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(City::class, 'c');
            
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    #[Route('/deleteCity/{id}', name: 'deleteCity')]
    public function deleteCity(int $id, CityRepository $cityRepository,EntityManagerInterface $em){

        $toDelete = $cityRepository->findOneById($id);
        if($toDelete->getIsActive() == 0){
            $toDelete->setIsActive(1);
        }else{
            $toDelete->setIsActive(0);
        }
        
        $em->persist($toDelete);
        $em->flush();

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(City::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    /**
     * @Route("/newCity", name="newCity", methods={"POST"})
     */
    public function newCity(CityRepository $cityRepository,Request $request, EntityManagerInterface $em): Response
    {

       $content = json_decode($request->getContent());
       $newCity = new City();
       $newCity->setName((string)$content->Name);
       $newCity->setZipCode((string)$content->ZipCode);
        
       $em->persist($newCity);
       $em->flush();

       $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(City::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    #[Route('/getLocation', name: 'getLocation')]
    public function getLocation(EntityManagerInterface $em, LocationRepository $LocationRepository){


        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(Location::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(),"Ville"=>$item->getCity()->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    #[Route('/deleteLocation/{id}', name: 'deleteLocation')]
    public function deleteLocation(int $id, LocationRepository $LocationRepository,EntityManagerInterface $em){

        $toDelete = $LocationRepository->findOneById($id);
        if($toDelete->getIsActive() == 0){
            $toDelete->setIsActive(1);
        }else{
            $toDelete->setIsActive(0);
        }
        $em->persist($toDelete);
        $em->flush();

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(Location::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(),"Ville"=>$item->getCity()->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    /**
     * @Route("/newLocation", name="newLocation", methods={"POST"})
     */
    public function newLocation(CityRepository $cityRepository, LocationRepository $LocationRepository,Request $request, EntityManagerInterface $em): Response
    {

       $content = json_decode($request->getContent());
       $newLocation = new Location();
       $newLocation->setName((string)$content->Name);
       $newLocation->setAdress((string)$content->Adress);
       $newLocation->setLatitude((string)$content->Latitude);
       $newLocation->setLongitude((string)$content->Longitude);
       $newLocation->setCity($cityRepository->findOneById($content->CityId));
        
       $em->persist($newLocation);
       $em->flush();

       $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(Location::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(),"Ville"=>$item->getCity()->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    #[Route('/getUsers', name: 'getUsers')]
    public function getUsers(UserRepository $UserRepository, EntityManagerInterface $em)
    {
        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(User::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "Surname"=>$item->getSurname(), "Pseudo"=>$item->getPseudo(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    #[Route('/deleteUser/{id}', name: 'deleteUser')]
    public function deleteUser(int $id, UserRepository $UserRepository,EntityManagerInterface $em){

        $toDelete = $UserRepository->findOneById($id);
        if($toDelete->getIsActive() == 0){
            $toDelete->isActive(1);
        }else{
            $toDelete->isActive(0);
        }
        $em->persist($toDelete);
        $em->flush();

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(User::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "Surname"=>$item->getSurname(), "Pseudo"=>$item->getPseudo(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    #[Route('/getSite', name: 'getSite')]
    public function getSite(SiteRepository $SiteRepository, EntityManagerInterface $em){
        

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(Site::class, 'c');
            
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    #[Route('/deleteSite/{id}', name: 'deleteSite')]
    public function deleteSite(int $id, SiteRepository $SiteRepository,EntityManagerInterface $em){

        $toDelete = $SiteRepository->findOneById($id);
        if($toDelete->getIsActive() == 0){
            $toDelete->setIsActive(1);
        }else{
            $toDelete->setIsActive(0);
        }
        
        $em->persist($toDelete);
        $em->flush();

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(Site::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }

    /**
     * @Route("/newSite", name="newSite", methods={"POST"})
     */
    public function newSite(SiteRepository $SiteRepository,Request $request, EntityManagerInterface $em): Response
    {

       $content = json_decode($request->getContent());
       $newSite = new Site();
       $newSite->setName((string)$content->Name);
       $newSite->setIsActive(1);
        
       $em->persist($newSite);
       $em->flush();

       $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(Site::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "IsActive"=>$item->getIsActive()));
        }
        return $this->json($data);
    }



    #[Route('/getState', name: 'getState')]
    public function getState(StateRepository $StateRepository, EntityManagerInterface $em){
        

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(State::class, 'c');
            
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName()));
        }
        return $this->json($data);
    }

    #[Route('/deleteState/{id}', name: 'deleteState')]
    public function deleteState(int $id, StateRepository $StateRepository,EntityManagerInterface $em){

        $toDelete = $StateRepository->findOneById($id);

        
        $em->persist($toDelete);
        $em->flush();

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(State::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName()));
        }
        return $this->json($data);
    }

    /**
     * @Route("/newState", name="newState", methods={"POST"})
     */
    public function newState(StateRepository $StateRepository,Request $request, EntityManagerInterface $em): Response
    {

       $content = json_decode($request->getContent());
       $newState = new State();
       $newState->setName((string)$content->Name);
        
       $em->persist($newState);
       $em->flush();

       $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(State::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName()));
        }
        return $this->json($data);
    }
    

    #[Route('/uploadFileUser', name: 'uploadFileUser', methods:['POST'])]
    public function uploadFileUser(Request $request, EntityManagerInterface $em, SiteRepository $siteRepository):Response {

        $content = json_decode($request->getContent());

        $path = $this->getParameter('kernel.project_dir').'/public/uploads/csv/users/user.csv';  
        $ifp = fopen( $path , 'wb' ); 
        $data = explode( ',', (string)$content->Base64);
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );

        $csvFile = file($path);
        for($i = 1; $i < count($csvFile); $i++)
        {
            $user = new User();
            $user->setSurname(str_getcsv($csvFile[$i])[0]);
            $user->setName(str_getcsv($csvFile[$i])[1]);
            $user->setMail(str_getcsv($csvFile[$i])[2]);
            $user->setPseudo(str_getcsv($csvFile[$i])[3]);
            $user->setPassword(str_getcsv($csvFile[$i])[3]);
            $site = $siteRepository->findOneById(str_getcsv($csvFile[$i])[4]);
            $user->setSite($site);
            $user->setTel("0606060606");
            $user->isActive(true);
            $em->persist($user);
       
        }
        $em->flush();

        $qb = $em->createQueryBuilder();
        $qb->select('c')
            ->from(User::class, 'c');
            //->where('c.isActive = 1');
        $query = $qb->getQuery();
        $result = $query->getResult();

        $data = [];
        foreach($result as $item){
            array_push($data, array("Id"=>$item->getId(), "Name"=>$item->getName(), "Surname"=>$item->getSurname(), "Pseudo"=>$item->getPseudo(), "IsActive"=>$item->getIsActive()));
        }

        return $this->json($data);
    }
}
