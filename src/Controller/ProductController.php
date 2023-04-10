<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * This function display all product 
     *
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/product', name: 'app_product')]
    public function index(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response
    {

        $products = $paginator->paginate(
            $entityManager->getRepository(Product::class)->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
    

        if (!$products) {
            throw $this->createNotFoundException(
                'No product found in the our DATABASE !'
            );
        }

        return $this->render('Products\index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/{id}', name: 'details_product')]
    public function detailsP(Product $product): Response
    {

        return $this->render('Products\ProductDetails.html.twig', [
               'product' => $product,
        ]);
    }

    #[Route('/home', name: 'home_Page')]
    public function home(): Response
    {
    
        return $this->render('Home2.html.twig');
    }
    #[Route('/', name: 'home_Page')]
    public function home2(): Response
    {
    
        return $this->render('Home2.html.twig');
    }

  
    #[Route('/product/add/new', name: 'product_new' , methods:['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$product` variable has also been updated
            $product = new Product();
            $product = $form->getData();
                    $entityManager->persist($product);
                    $entityManager->flush();
            // ... perform some action, such as saving the task to the database
            $this->addFlash(
               'success',
               'The product '.$product->getname().' was added successfully!'
            );
              return $this->redirectToRoute('app_product');
        }
        return $this->render('Products\new.html.twig',
        ['form' => $form->createView() 
    ]);
    }

    #[Route('/product/edit/{id}', name: 'product_edit' , methods:['GET','POST'])]
     public function edit(EntityManagerInterface $entityManager,Product $product,Request $request):Response
     {
        // $product = $entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$product` variable has also been updated
            $product = $form->getData();
                    $entityManager->persist($product);
                    $entityManager->flush();
            // ... perform some action, such as saving the task to the database
            $this->addFlash(
               'success',
               'The product '.$product->getname().' was edited successfully!'
            );
              return $this->redirectToRoute('app_product');
        }



        return $this->render('Products\edit.html.twig',
        ['form' => $form->createView() ,
          'product'=>$product
    ]);

    }

    #[Route('/product/delete/{id}', name: 'product_delete' , methods:['GET','POST'])]
    public function deleteP(Product $product,EntityManagerInterface $entityManager){


        $entityManager->remove($product);
        $entityManager->flush();
        $this->addFlash(
            'danger',
            'The product '.$product->getname().' was Deleted successfully!'
         );
        return $this->redirectToRoute('app_product');  
      } 

      #[Route('/category', name: 'app_category')]
    public function categories(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response
    {

        $category = $paginator->paginate(
            $entityManager->getRepository(Category::class)->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );
    

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found in our DATABASE !'
            );
        }

        return $this->render('Categories\categories.html.twig', [
            'categories' => $category,
            
        ]);
    }

    
}

 
    
