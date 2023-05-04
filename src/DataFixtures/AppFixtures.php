<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
   public function load(ObjectManager $manager): void
{  
    $users = [];
    $generator = Factory::create("fr_FR");
    $products = [];
    $plaintextPassword = '123';
    // create admin user
    $admin = new User();
    $admin->setfUllName('Admin')
          ->setEmail('mohhd2045@gmail.com')
          ->setRoles(['ROLE_ADMIN'])
          ->setplainPassword($plaintextPassword);


    $manager->persist($admin);

    // create 9 client users
  

    // create 5 categories
    for ($i = 1; $i <= 5; $i++) { 
        $category = new Category();
        $category->setName('Category '.$i)
                 ->setImgPath('images/img1'.$i.'.png');
        $manager->persist($category);

        // create 5 products for each category
        for ($j = 1; $j <= 5; $j++) { 
            $product  = new Product(); 
            $product->setName($generator->word().' '.$generator->word())
                    ->setPrice(mt_rand(30,300)/2.6767)
                    ->setDescription($generator->text(20))
                    ->setImgPath("images/img(".mt_rand(1,19).").jpg")
                    ->setinStock(mt_rand(1,100))
                    ->setCategory($category);
            $products[] = $product;
            $manager->persist($product);
        }
    }
    for ($i = 1; $i <= 9; $i++) {
        $user = new User();
        
        $user->setfUllName($generator->name())
            ->setEmail($generator->email())
            ->setRoles(['ROLE_CLIENT'])
            ->setPlainPassword($plaintextPassword);

        for ($j = 0; $j < 5; $j++) { 
            $product = $products[mt_rand(0, count($products) - 1)];
            $user->addCart($product);
        }
        $users[] = $user;
        $manager->persist($user);
    }
    // add at most 7 products to each client's cart
    foreach ($users as $user) { 
        $maxProductsInCart = 7;
        $numProducts = mt_rand(1, $maxProductsInCart);
        for ($i = 0; $i < $numProducts; $i++) { 
            $product = $products[mt_rand(0, count($products) - 1)];
            $user->addCart($product);
        }
        $manager->persist($user);
    }

    $manager->flush();
}

}