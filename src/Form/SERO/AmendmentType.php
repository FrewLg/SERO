<?php

namespace App\Form\SERO;

use App\Entity\SERO\Amendment;
use App\Entity\SERO\Application;
use App\Entity\SERO\AttachmentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmendmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('purpose', TextareaType::class,[
                'label'=>"Purpose",
                'required'=>1,
                'attr'=>['class'=>'form-control', 'placeholder'=>"Purpose" ]
            ])
            ->add('changes', TextareaType::class,[
                'label'=>"Changes Made",
                'required'=>1,
                'attr'=>['class'=>'form-control', 'placeholder'=>"Changes Made" ]
            ])
            ->add('attachment', FileType::class, [
                'label' => 'Upload attachment',
                'mapped' => false, 'attr' => [
                    'class' => 'form-control my-4' ,
                ],
                'required' => true,
            ])
            ->add('attachmentType', EntityType::class, [
                'label' => 'Attachment type ',
                'placeholder' => '-- Select Attachment type --',
                'class' => AttachmentType::class,
                'mapped' => true,
                'attr' => [
                    'class' => 'form-control col-12 select2 my-4' ,
                 ],
                'required' => true,

                'choice_label'=>'name',
             ])
            // 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Amendment::class,
        ]);
    }
}
