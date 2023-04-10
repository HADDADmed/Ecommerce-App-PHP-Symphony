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

        $generator = Factory::create("fr_FR");

        for ($j=1; $j <5 ; $j++) { 
            $category = new Category();
            $category->setName('cat'.$j)->setImgPath("images/img1(".mt_rand(1,4).").png");
            $manager->persist($category);
           
          for($i=1;$i<=5;$i++){
            $product  = new Product();
            $id = mt_rand(0,3);   
             $product->setname($generator->word().' '.$generator->word())
                 ->setPrice(mt_rand(30,300)/2.6767)
                 ->setDescription($generator->text(70))
                 ->setImgPath("images/img(".mt_rand(1,19).").jpg")
                 ->setinStock(mt_rand(1,100))
                 ->setCategory($category);
              $manager->persist($product);
         }

        };
        //  // ... e.g. get the user data from a registration form
        

        // // ...
                for ($i=0; $i < 15; $i++) { 
                    
                    $user = new User();
                    $plaintextPassword = '123';
    
                   
                    $user->setfUllName($generator->name())
                        ->setEmail($generator->email())
                        ->setRoles(['User_Role'])
                        ->setPlainPassword($plaintextPassword);
                     $manager->persist($user);
  
                }
        $manager->flush();
    }
}
