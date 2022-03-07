<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    
    #[Route('/profile/{id}', name: 'profile', requirements: ['id'=> '\d+'])]
    public function profile(Request $request,$id, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userrepo): Response
    {
        $user = $userrepo ->findOneById($id);
       

        return $this->render('main/profile.html.twig', compact("id", "user"));
    }
   #[Route('/edit/{id}', name: 'edit' , requirements: ['id'=> '\d+'])]
    public function edit(Request $request, $id, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userrepo): Response
    {
        $user = $userrepo ->findOneById($id);
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // encode the plain password
            $user->isActive(true);
            $uploadedFile = $form['profilePicture']->getData();  
        if($uploadedFile){
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/profilepicture';  
            $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );
        $user->setPhoto($newFilename);
      }
      $entityManager->persist($user);
      $entityManager->flush();
        $this ->addFlash('success', 'Profile Edited');
        return $this->redirectToRoute('profile', ['user' =>$user, 'id' => $id]);

        
    }
    return $this->render('main/edit.html.twig', [
        'profileForm' => $form->createView(),
    ]);
    
}
    
 

      

}

