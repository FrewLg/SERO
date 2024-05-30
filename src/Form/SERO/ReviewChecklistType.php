<?php

namespace App\Form\SERO;

use App\Entity\SERO\ReviewChecklist;
use App\Entity\SERO\ReviewChecklistGroup;
use App\Form\AnswersType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewChecklistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name'
            , TextType::class, [
                'label' => "Question",
                // 'choice_label' => 'name',
                'attr'=> [
                'placeholder' => "Question",
                    
                    'class'=>'form-control form-control-lg form-control-solid    mb-4 p-4'],
            ])
            
            ->add('answers', CollectionType::class, [
                'entry_type' => AnswersType::class,
                // 'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'attr'=> ['class'=>'form-control form-control-lg form-control-solid    '],
                // 'data_class' => null,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            
            ->add('checklistGroup' , EntityType::class, [
                'class' => ReviewChecklistGroup::class,
                'choice_label' => 'name',
                'placeholder'=>"--Select question section--",
                'attr'=> ['class'=>'form-control form-control-lg form-control-solid  select2 mb-4 p-4'],
            ])
 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReviewChecklist::class,
        ]);
    }
}
