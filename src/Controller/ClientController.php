<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
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
    #[Route('/', name: 'home_Page')]
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
            return $this->render('admin/homePage.html.twig', [
                'user' => $user
            ]);
        }
    
        return $this->render('home2.html.twig');
    }


    #[Route('/user/cart', name: 'cart_Page')]
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

            $userId = $this->getUser()->getId();
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








}    
