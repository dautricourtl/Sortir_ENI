<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPassType;
use App\Form\NewPasswordType;
use App\Form\ForgotPassPseudoType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
           
            
            $user->isActive(true);
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )             
            );
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
            // do anything else you need here, like send an email

            $this ->addFlash('success', 'Utilisateur inscrit');
            return $this->redirectToRoute('main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/forgotPassword/{pseudo}', name: 'forgotpass')]
    public function forgotpass(Request $request, UserRepository $userRepository, EntityManagerInterface $em, string $pseudo): Response
    {
        $user = $userRepository->findOneByPseudo($pseudo);
        $tmpUser = new User();
        $form = $this->createForm(ForgotPassType::class, $tmpUser);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($tmpUser->getReponse() == $user->getReponse()){
                $newToken = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
            $user->setToken($newToken);
            $em->persist($user);
            $em->flush();
                return $this->redirectToRoute('newpass', ['token' =>$user->getToken()]);
                
            }else{
                $this ->addFlash('danger', 'Réponse incorecte');
            }
        }

   
    return $this->render('/main/forgotPassword.html.twig', [
        'ForgotPassForm' => $form->createView(),
        "question" => $user->getQuestion()->getQuestion()
    ]);
    }

    #[Route('/newPassword/{token}', name: 'newpass')]
    public function newpassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em, string $token, UserRepository $UserRepository): Response
    {
        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);
        $user = $UserRepository->findOneByToken($token);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
           
            
        
            $user->isActive(true);
            $user->setPassword(
            $userPasswordHasher->hashPassword($user,
                    $form->get('plainPassword')->getData()
               )             
           );
           $em->persist($user);
            $em->flush();
           return $this->redirectToRoute('main');


       }
           // do anything else you need here, like send an email
           return $this->render('main/newPassword.html.twig', [
               'newPasswordForm' => $form->createView(),
           ]);

           
       }

       #[Route('/forgotPassPseudo', name: 'forgotpasspseudo')]
       public function profile(Request $request ): Response
       {
           
        $tmpUser = new User(); 
        $form = $this->createForm(ForgotPassPseudoType::class, $tmpUser);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
        return $this->redirectToRoute('forgotpass', ['pseudo' =>$tmpUser->getPseudo()]);
        }
        return $this->render('main/forgotPasswordPseudo.html.twig', [
            'ForgotPassPseudoForm' => $form->createView(),

            
        ]);
       }

}

  