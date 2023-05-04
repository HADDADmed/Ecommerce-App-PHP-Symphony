<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'security.registration' , methods:['GET','POST'])]
    public function newUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$user` variable has also been updated
            $user = $form->getData();
                    $entityManager->persist($user);
                    $entityManager->flush();
            // ... perform some action, such as saving the task to the database
            $this->addFlash(
               'success',
               'The user was added successfully!'
            );
              return $this->redirectToRoute('security.login');
        }
        return $this->render('security\registration.html.twig',
        ['form' => $form->createView() 
         ]);
    }


    #[Route('/Users/edit/{id}', name: 'user.edit' , methods:['GET','POST'])]
    public function editUser(Request $request, EntityManagerInterface $entityManager,User $user): Response
    {

            if(!$this->getUser()){

                return $this->redirectToRoute('security.login');
            }

            if($this->getUser() === $user){
                $form = $this->createForm(UserType::class, $user);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    // $form->getData() holds the submitted values
                    // but, the original `$user` variable has also been updated
                    $user = $form->getData();
                            $entityManager->persist($user);
                            $entityManager->flush();
                    // ... perform some action, such as saving the task to the database
                    $this->addFlash(
                    'success',
                    'The user info was edited successfully!'
                    );

                    return $this->redirectToRoute('home_Page');


            }

    
        

        
        }
        return $this->render('security\registration.html.twig',
        ['form' => $form->createView() 
         ]);
    }




}
