<?php

namespace App\Form;

use App\Entity\Profile;
use App\Entity\UserInfo\WorkExperience;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orgName' ,
            TextType::class,
            [
                'label'=>'Organization',
                'attr' => ['class' => 'form-control',
                'placeholder'=>'Organization',
                
                ]
            ]
        )
            ->add('jobPosition',
            TextType::class,
            [
                'label'=>'Job Position',
                'attr' => ['class' => 'form-control',
                'placeholder'=>'Job Position',
                
                ]
            ]
        )
            ->add('dateStarted', null, [
                'widget' => 'single_text',
            ])
            ->add('dateLeft', null, [
                'widget' => 'single_text',
            ])
            ->add('responsibilities',
            TextType::class,
            [
                'label'=>'Responsibilities',
                'attr' => ['class' => 'form-control',
                'placeholder'=>'Enter Responsibilities',
                
                ]
            ]
        )
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkExperience::class,
        ]);
    }
}
