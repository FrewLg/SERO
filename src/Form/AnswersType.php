<?php

namespace App\Form;

use App\Entity\SERO\Choice;
use App\Entity\SERO\ReviewChecklist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('choiceName', TextType::class, [
                'attr' => ['class' => 'form-control', 
                'placeholder'=>'Enter choice',
            ],
                // 'data_class' => null,
                // 'mapped'=>false,
                'label'=>'Choice:',

            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Choice::class,
        ]);
    }
}
