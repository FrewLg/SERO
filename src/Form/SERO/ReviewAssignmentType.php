<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\ReviewAssignment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewAssignmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('closed')
            ->add('allowToView')
            // ->add('application', EntityType::class, [
            //     'class' => Application::class,
            //     'choice_label' => 'id',
            // ])
            ->add('irbreviewer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReviewAssignment::class,
        ]);
    }
}
