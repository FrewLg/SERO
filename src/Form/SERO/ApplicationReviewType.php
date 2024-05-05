<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\ApplicationReview;
use App\Entity\SERO\ReviewStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checked')
            ->add('application', EntityType::class, [
                'class' => Application::class,
                'choice_label' => 'id',
            ])
            ->add('review', EntityType::class, [
                'class' => ReviewStatus::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApplicationReview::class,
        ]);
    }
}
