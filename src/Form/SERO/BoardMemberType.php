<?php

namespace App\Form\SERO;

use App\Entity\SERO\BoardMember;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('assignedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('status'
            , ChoiceType::class, [
                // "placeholder" => "Select Status",
              'expanded'=>true,
                "required" => true,
                // "choices" => [
                //     "Chair" => BoardMember::ROLE_CHAIR,
                //     "Vice Chair" => BoardMember::ROLE_VICE_CHAIR,
                //     "Secretary" => BoardMember::ROLE_SECRETARY,
                //     "Coordinator" => BoardMember::ROLE_COORDINATOR, 
                //     "Member" => BoardMember::ROLE_MEMBER,
                // ],
                "attr" => [
                    "class" => " "
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'profile',
                'attr'=>['class'=>'board_member_user form-control col-6', 'id'=>'']
            ])
            
            ->add('role', ChoiceType::class, [
                "placeholder" => "Select Role",
              
                "required" => true,
                "choices" => [
                    "Chair" => BoardMember::ROLE_CHAIR,
                    "Vice Chair" => BoardMember::ROLE_VICE_CHAIR,
                    "Secretary" => BoardMember::ROLE_SECRETARY,
                    "Coordinator" => BoardMember::ROLE_COORDINATOR, 
                    "Member" => BoardMember::ROLE_MEMBER,
                ],
                "attr" => [
                    "class" => "board_role form-control col-4"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BoardMember::class,
        ]);
    }
}
