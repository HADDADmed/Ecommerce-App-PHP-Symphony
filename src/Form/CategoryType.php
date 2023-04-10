<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
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
            ]
        ])
        ->add('imgPath',TextType::class,[
            'attr'=>[
                'class'=>'form-control'
            ],
            'label'=>'imgPath :',
            'label_attr'=>[
                'class'=>'form-label'
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
            'data_class' => Category::class,
        ]);
    }
}
