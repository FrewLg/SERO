<?php

namespace App\Form;

use App\Entity\SERO\BoardMember;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $roles = [
            'Super Admin' => 'ROLE_SUPER_ADMIN',
            'Admin' => 'ROLE_ADMIN',
            "Chair" => "ROLE_CHAIR",
            "Vice Chair" =>  'ROLE_VICE_CHAIR',
            "Secretary" => 'ROLE_SECRETARY',
            "Coordinator" =>  'ROLE_COORDINATOR',
            "Member" =>  'ROLE_MEMBER',
        ];
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'label' => 'form.label.role',
                'choices' => $roles,
                'required' => true,
                'multiple' => true,
                'expanded' => false
            ])
            // ->add('password')
            // ->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
