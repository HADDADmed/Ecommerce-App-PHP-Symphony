<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Name :',
                'label_attr'=>[
                    'class'=>'form-label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                ]
            ])
            ->add('price',MoneyType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Price :',
                'label_attr'=>[
                    'class'=>'form-label'
                ],
                'constraints'=>[
                   new Assert\NotBlank,
                  ]
            ])
            ->add('description',TextareaType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Description  :',
                'label_attr'=>[
                    'class'=>'form-label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                ]
            ])
            ->add('imgPath',TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'imgPath :',
                'label_attr'=>[
                    'class'=>'form-label'
                ],
                // 'constraints'=>[
                //     new Assert\NotBlank(),
                // ]
            ])
            ->add('inStock',NumberType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label'=>'Quatity :',
                'label_attr'=>[
                    'class'=>'form-label'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                ]
            ])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'choice_label' => 'name',
                'attr'=>[
                    'class'=>'form-control'
                ]
           ])
           ->add('submit',SubmitType::class,[
            'attr'=> [
                'class'=>'btn btn-primary mt-4'
            ]
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
