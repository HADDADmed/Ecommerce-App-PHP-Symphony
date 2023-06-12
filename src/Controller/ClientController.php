<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Form\CategoryType;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends AbstractController
{
    #[Route('/', name: 'home_Page', )]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Check if user is authenticated
        if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('home2.html.twig');
        }
    
        // Get user object and check roles
        $user = $this->getUser();
        $roles = $user->getRoles();
    
        if (in_array('ROLE_CLIENT', $roles)) {

            $products = $entityManager->getRepository(Product::class)
                                ->findAll();
            if (!$products) {
                throw $this->createNotFoundException(
                    'No product found in the our DATABASE !'
                );
            }
            return $this->render('client/homePage.html.twig', [
                'user' => $user,
                'products' =>$products 
            ]);
        }
        elseif (in_array('ROLE_ADMIN', $roles)) {
            
            $users = [];
            $users1 = $entityManager->getRepository(User::class)->findAll();
            foreach ($users1 as $user) {
                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    continue;
                }
                $users[] = $user;
            }
            return $this->render('admin/homePage.html.twig', [
                'users' => $users
            ]);
        }
    
        return $this->render('home2.html.twig');
    }


    #[Route('/user/cart/', name: 'cart_Page')]
    public function cart(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request ): Response
    {
        // Check if user is authenticated
        if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('home2.html.twig');
        }
    
        // Get user object and check roles
        $user = $this->getUser();
        $roles = $user->getRoles();
    

       
        if (in_array('ROLE_CLIENT', $roles)) {

            $products = $this->getUser()->getCart();                 
            if (!$products) {
                throw $this->createNotFoundException(
                    'No product found in the our DATABASE !'
                );
            }
            $totalPrice =0.0;
            foreach ($products as $product) {
                $totalPrice += $product->getprice();
            }
            return $this->render('client\cart.hml.twig', [
                'user' => $user,
                'products' =>$products ,
                'totalPrice'=>$totalPrice
            ]);
        }
       
    
        return $this->render('home2.html.twig');
    }

    #[Route('/user/cart/{id}', name: 'cart_Page_Admin',methods:['GET','POST'])]
    public function cartAdmin(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request ,User $user): Response
    {
        // Check if user is authenticated
        if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('home2.html.twig');
        }

            $products = $user->getCart();                 
            if (!$products) {
                throw $this->createNotFoundException(
                    'No product found in the our DATABASE !'
                );
            }
            $totalPrice =0.0;
            foreach ($products as $product) {
                $totalPrice += $product->getprice();
            }
            return $this->render('admin\cart.html.twig', [
                'user' => $user,
                'products' =>$products ,
                'totalPrice'=>$totalPrice
            ]);

    }



    #[Route('/user/delete/{id}', name: 'user_delete' , methods:['GET','POST'])]
    public function deleteP(User $user,EntityManagerInterface $entityManager){


        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash(
            'danger',
            'The product '.$user->getfUllName().' was Deleted successfully!'
         );
        return $this->redirectToRoute('home_Page');  
      } 


      #[Route('/cart/add/{id}',name:"addToCart",methods:['GET','POST'])]
      public function addToCart (int $id ,EntityManagerInterface $entityManager): Response{
 
         if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
             return $this->render('home2.html.twig');
         }
         $user = $this->getUser();
         $product = $entityManager->getRepository(Product::class)
                                ->findOneBy(['id' => $id]);
 
         $cartProducts1= $user->getCart();
         $cartProducts = [];
         foreach ($cartProducts1 as $cartProduct1 ) {
            $cartProducts[] = $cartProduct1;
         }
           if (in_array($product, $cartProducts)){
 
             $this->addFlash(
                'danger',
                'Product Already existe in you cart List '
             );
             return $this->redirectToRoute('home_Page');  
           }else
         {
             $user->addCart($product);
             $entityManager->persist($user);
             $entityManager->flush();
 
               $this->addFlash(
                  'success',
                  'Product Added successfully '
               );
               return $this->redirectToRoute('home_Page');  
         }

         return $this->render('home2.html.twig');
 
      }


      #[Route('/cart/delete/{id}',name:"deleteFromCart",methods:['GET','POST'])]
      public function deleteFromCart (int $id ,EntityManagerInterface $entityManager): Response{
 
         if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
             return $this->render('home2.html.twig');
         }
            $user = $this->getUser();
            $product = $entityManager->getRepository(Product::class)
                                    ->findOneBy(['id' => $id]);
    
         
             $user->removeCart($product);
             $entityManager->persist($user);
             $entityManager->flush();
 
               $this->addFlash(
                  'danger',
                  'Product deleted successfully  from ure cart'
               );
               return $this->redirectToRoute('cart_Page');  
         

 
      }

      #[Route('/cart/delete/',name:"deleteCart",methods:['GET','POST'])]
      public function deleteCart (EntityManagerInterface $entityManager): Response{
 
         if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
             return $this->render('home2.html.twig');
         }
            $user = $this->getUser();
            $products = $entityManager->getRepository(Product::class)
                                    ->findAll();
    
             foreach ($products as $product) {
                $user->removeCart($product);
             }
             $entityManager->persist($user);
             $entityManager->flush();
 
               $this->addFlash(
                  'danger',
                  'Products deleted successfully  from ure cart'
               );
               return $this->redirectToRoute('cart_Page');  
         

 
      }
      #[Route('/client', name: 'app_client')]
    public function indexj(EntityManagerInterface $entityManager): Response
    {
        // Check if user is authenticated 
        if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new Response('<h1>Hello HOME</h1>');
        }

        // Get user object and check roles
        $user = $this->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_CLIENT', $roles)) {
            $products = $entityManager->getRepository(Product::class)
                                ->findAll();
           
            if (!$products) {
                throw $this->createNotFoundException(
                    'No product found in the our DATABASE !'
                );
            }
            return new Response('<h1>Hello client</h1>');
        }
        elseif (in_array('ROLE_ADMIN', $roles)) {
            
            $users = [];
            $users1 = $entityManager->getRepository(User::class)->findAll();
            foreach ($users1 as $user) {
                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    continue;
                }
                $users[] = $user;
            }
            return new Response('<h1>Hello ADMIN</h1>');
    }
    return new Response('<h1>Hello HOME</h1>');
}


    
}    
