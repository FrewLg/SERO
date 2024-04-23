<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            // ->add('startDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('endDate', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('location')
            ->add('description')
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
