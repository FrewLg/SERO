<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\AttachmentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('attachment', FileType::class, [
                'label' => 'Upload attachment',
                'mapped' => false, 'attr' => [
                    'class' => 'form-control my-4' ,
                ],
                'required' => true,
            ])
            ->add('attachmentType', EntityType::class, [
                'label' => 'Attachment type',
                'placeholder' => '-- Select Attachment type --',
                'class' => AttachmentType::class,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control select2 my-4' ,
                 ],
                'required' => true,

                'choice_label'=>'name',
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
