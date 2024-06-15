<?php

namespace App\Form;

use App\Entity\Directorate;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Profile;
use App\Entity\User;
use App\Entity\UserInfo\WorkExperience;
// use Doctrine\DBAL\Types\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'attr' => ['class' => 'form-control',
                'placeholder'=>'First Name',
                    
                    ]
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'attr' => ['class' => 'form-control',
                'placeholder'=>'Last Name',
                
                ]
                ]
            )
            ->add(
                'alternativeEmail',
                TextType::class,
                [
                    'attr' => ['class' => 'form-control',
                'placeholder'=>'Alternative Email',
                
                ]
                ]
            )
            // ->add('department')
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'attr' => ['class' => 'form-control',
                'placeholder'=>'Phone number',
                ]
                ]
            )
            ->add(
                'birthDate',
                DateType::class,
                array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'data' => (new DateTime('+10 day')),
                    'attr' => array(
                        // 'min' => (new DateTime('-1 year'))->format('Y-m-d'),
                        'max' => (new DateTime('+1 month'))->format('Y-m-d'),
                        'required' => true,
                        'class' => 'form-control',
                    )
                )
            )
            ->add(
                'title',
                TextType::class,
                [
                    'attr' => ['class' => 'form-control',
                'placeholder'=>'Title',
                ]
                ]
            )
            ->add(
                'signature',
                TextType::class,
                [
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add('workExperiences', CollectionType::class, [
                'entry_type' => WorkExperienceType::class,
                // 'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                // 'data_class' => null,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('dirctorate', EntityType::class, [
                'class' => Directorate::class,
                'choice_label' => 'name',
                'label' => 'Directorate',
                'placeholder' => '--Select your directorate--',

                'attr' => [
                    'class' => 'select2 form-control',
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}


 