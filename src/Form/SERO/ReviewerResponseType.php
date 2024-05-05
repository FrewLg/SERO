<?php

namespace App\Form\SERO;

use App\Entity\SERO\ReviewChecklist;
use App\Entity\SERO\ReviewerResponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewerResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('answer')
            ->add('checklist', EntityType::class, [
                'class' => ReviewChecklist::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReviewerResponse::class,
        ]);
    }
}
