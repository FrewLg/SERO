<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\ParticipatingInvestigator;
use PHPUnit\Framework\Test;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipatingInvestigatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class,[
                'attr'=>[
                    'class'=>'form-control'
,
                    'placeholder'=>'Full Name'
                    ]
            ])
            ->add('licenceNumber', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Enter licence Number'
                    ]
            ])
            ->add('institution', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Enter institution'
                    ]
            ])
            ->add('telephone', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Enter telephone'
                ]
            ])
            ->add('email', TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'placeholder'=>'Enter email'
                    ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParticipatingInvestigator::class,
        ]);
    }
}
