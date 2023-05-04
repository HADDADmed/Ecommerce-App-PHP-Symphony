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
use Doctrine\ORM\PersistentCollection\getTotalItemCount;


class CetegoryController extends AbstractController
{

    #[Route('/category', name: 'app_category')]
    public function categories(EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response
    {

        if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('home2.html.twig');
        }

        $user = $this->getUser();
        $roles = $user->getRoles();
    
        if (in_array('ROLE_ADMIN', $roles)) {

           
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
        
        }else  if (in_array('ROLE_CLIENT', $roles)) {

           
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
            
                    return $this->render('client\categories.html.twig', [
                        'categories' => $category,
                        
                    ]);
        
        }
    }

    #[Route('/category/add/new', name: 'category_new' , methods:['GET','POST'])]
    public function newCat(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$product` variable has also been updated
            $category = new Category();
            $category = $form->getData();
                    $entityManager->persist($category);
                    $entityManager->flush();
            // ... perform some action, such as saving the task to the database
            $this->addFlash(
               'success',
               'The product '.$category->getname().' was added successfully!'
            );
              return $this->redirectToRoute('app_category');
        }
        return $this->render('Categories\new.html.twig',
        ['form' => $form->createView() 
    ]);
    }

    #[Route('/category/{id}', name: 'category_d')]
    public function category(Category $category,EntityManagerInterface $entityManager,PaginatorInterface $paginator,Request $request): Response
    {
        if (!$this->getUser() || !$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('home2.html.twig');
        }

        $user = $this->getUser();
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $categoryP = $paginator->paginate(
                $category->getProducts(),
                $request->query->getInt('page', 1), /*page number*/
                3 /*limit per page*/
            );
        
            if (!$categoryP) {
                throw $this->createNotFoundException(
                    'No product found in the our DATABASE !'
                );
            }
                     $cat = $category->getName();

        return $this->render('Categories\category.html.twig', [
            'products' => $categoryP,
            'cat' => $cat,
             'productcount'=> $category->getProducts()->count()
             ]);
            }elseif (in_array('ROLE_CLIENT', $roles)) {
                $categoryP =  $category->getProducts();
            
                if (!$categoryP) {
                    throw $this->createNotFoundException(
                        'No product found in the our DATABASE !'
                    );
                }
                         $cat = $category->getName();
                $mssg = 'category :'.$cat;
                       return $this->render('client\homePage.html.twig', [
               'products' => $categoryP,
                  'mssg' => $mssg,
                 'productcount'=> $category->getProducts()->count()
             ]);
            }
            return $this->render('home2.html.twig');
    }

    #[Route('/product/deletepc/{id}', name: 'product_deletepc' , methods:['GET','POST'])]
    public function deletePc(EntityManagerInterface $entityManager ,int $id){

        $product=$entityManager->getRepository(Product::class)->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('category_d',[
                'id' =>$product->getCategory()->getId()
        ]);  
      } 
      #[Route('/productCat/edit/{id}', name: 'product_editCatP' , methods:['GET','POST'])]
      public function editCatP(EntityManagerInterface $entityManager,int $id,Request $request):Response
      {
        $product=$entityManager->getRepository(Product::class)->find($id);
         $form = $this->createForm(ProductType::class, $product);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid()) {
             // $form->getData() holds the submitted values
             // but, the original `$product` variable has also been updated
             $product = $form->getData();
                     $entityManager->persist($product);
                     $entityManager->flush();
             // ... perform some action, such as saving the task to the database

             return $this->redirectToRoute('category_d',[
                'id' =>$product->getCategory()->getId()
        ]);  
         }
 
 
 
         return $this->render('Products\edit.html.twig',
         ['form' => $form->createView() 
     ]);

     
 

     }



}
