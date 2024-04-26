<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\AttachmentType;
use App\Entity\SERO\Version;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('changesMade', TextareaType::class, [
                'label' => 'Changes made on a new version',
                'mapped' => true, 'attr' => [
                    'class' => 'form-control  m-0   ',
                    'required' => true,
                ],
                'required' => true,
            ])
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
                'mapped' => true, 
                'attr' => [
                    'class' => 'form-control select2 my-4' ,
                 ],
                'required' => true,

                'choice_label'=>'name',
             ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Version::class,
        ]);
    }
}
