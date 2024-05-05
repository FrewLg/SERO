<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\Continuation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContinuationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('requestedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('description')
            // ->add('approved')
            // ->add('application', EntityType::class, [
            //     'class' => Application::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('requestedBy', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('approvedBy', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Continuation::class,
        ]);
    }
}
