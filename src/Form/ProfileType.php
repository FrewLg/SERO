<?php

namespace App\Form;

use App\Entity\Directorate;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('alternativeEmail')
            ->add('department')
            ->add('phoneNumber')
            ->add('birthDate')
            ->add('title')
            // ->add('nationalID')
            ->add('signature')
           
            ->add('dirctorate', EntityType::class, [
                'class' => Directorate::class,
'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}

  
 
class UserProfilePictureType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder

            ->add('image', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                "attr" => [
                    "accept" => "image/*",
                    "class" => "form-control form-group",

                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}