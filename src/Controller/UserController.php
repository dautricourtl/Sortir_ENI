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
    
    #[Route('/profile/{id}', name: 'profile')]
    public function profile(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userrepo, int $id): Response
    {
        $user = $userrepo ->findOneById($id);
       

        return $this->render('main/profile.html.twig', [
            'profil' =>$user,
        ]);
    }
   #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userrepo, int $id): Response
    {
        $user = $userrepo ->findOneById($id);
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->isActive(true);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('main');
        }

        return $this->render('main/edit.html.twig', [
            'profileForm' => $form->createView(),
        ]);
    }
}



