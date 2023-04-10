<?php


        namespace App\EntityListener ;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

        class UserListener
        {

            private UserPasswordHasherInterface $passwordHasher;

            public function __construct(UserPasswordHasherInterface $passwordHasher)
            {
                $this->passwordHasher = $passwordHasher;
            }

            public function prePersist(User $user){

                        $this->encodePassword($user);
            }
            public function preUpdate(User $user){

                $this->encodePassword($user);

            }

            /**
             * Encode Password based on plain password 
             *
             * @param User $user
             * @return void
             */
            public function encodePassword(User $user){

                    
                    if($user->getplainPassword() === null)
                    return ;

                    $plaintextPassword = $user->getplainPassword();
            
                    // hash the password (based on the security.yaml config for the $user class)
                    $hashedPassword = $this->passwordHasher->hashPassword(
                        $user,
                        $plaintextPassword
                    );
                    $user -> setPassword($hashedPassword);
                    $user -> setPlainPassword('null');

            }


        }