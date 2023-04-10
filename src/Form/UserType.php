<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fUllName',TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'FullName :',
                'label_attr'=>[
                    'class'=>'form-label text-dark '
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                ]
            ])
            ->add('email',EmailType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Email :',
                'label_attr'=>[
                    'class'=>'form-label text-dark '
                ],
                'constraints'=>[
                    new Assert\Email(),
                    new Assert\NotBlank(),
                ]
            ])
            ->add('plainPassword',RepeatedType::class ,[

                'type'=>PasswordType::class,
                'first_options' =>[
                    'label'=>'Password :',
                    'label_attr'=>[
                        'class'=>'form-label text-dark '
                    ],
                    'attr'=>[
                        'class'=>'form-control'
                    ],
                    'constraints'=>[
                        new Assert\NotBlank(),
                    ]
                ],
                'second_options'=>[
                    'label'=>'Password confirmation :',
                    'label_attr'=>[
                        'class'=>'form-label text-dark '
                    ],
                    'attr'=>[
                        'class'=>'form-control'
                    ],
                    'constraints'=>[
                    new Assert\NotBlank(),
                ]
                ],
                'invalid_message'=>'the Passwords does not match try againe !'
            ])
            ->add('submit',SubmitType::class,[
                'attr'=> [
                    'class'=>'btn btn-primary mt-4 '
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
