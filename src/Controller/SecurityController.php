<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    // #[Route('/login', name: 'security.login', methods:['GET','POST'])]
    // public function index(): Response
    // {
    //     return $this->render('security/login1.html.twig', [
    //         'controller_name' => 'SecurityController',
    //     ]);
    // }
    
    #[Route('/login', name:'security.login', methods:['GET','POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
     {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

         return $this->render('security/login1.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
           
         ]);
     } 
     
     #[Route('/logout', name:'security.logout', methods:['GET','POST'])]
     public function logout(AuthenticationUtils $authenticationUtils): Response
      {
         

          return $this->render('security/login1.html.twig', );
      }

      #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    
   

      
  }

 