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
use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images/profilepicture';
                $newFilename = uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user->setPhoto($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $this->addFlash('success', 'Utilisateur inscrit');
            return $this->redirectToRoute('main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/forgotPassword', name: 'forgotpass')]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $pseudo = $request->query->get('pseudo', null);
        if (!$pseudo) {
            $user = null;
        } else {
            // on affiche une page qui demande la réponse
            $user = $userRepository->findOneByPseudo($pseudo);
        }
        $formView = null;
        if ($user) {
            // je veux un form pour avoir la réponse à sa question.
            $questionClass = new stdClass();
            $questionClass->reponse = null;
            $form = $this->createFormBuilder($questionClass)
                ->add('reponse', TextType::class, [
                    'label' => $user->getQuestion()->getQuestion(),
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 255]),
                    ],
                ])
                ->add('Envoyer', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid() && $user->isReponseValid($questionClass->reponse)) {
                $reponse = $questionClass->reponse;
                $newToken = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
                $user->setToken($newToken);
                $em->persist($user);
                $em->flush();
                    return $this->redirectToRoute('newpass', ['token' =>$user->getToken()]);
            }
            
            $formView = $form->createView();
        }
        return $this->render('/main/forgotPassword.html.twig', 
        [
            'user' => $user,
            'formView' => $formView,
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
                $userPasswordHasher->hashPassword(
                    $user,
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
}
