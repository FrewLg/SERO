<?php

namespace App\Form;

use App\Entity\Partner;
use App\Entity\Training;

use App\Entity\TrainingOrganizer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingOrganizerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createdAt')
//             ->add('training', EntityType::class, [
//                 'class' => Training::class,
// 'choice_label' => 'id',
//             ])    n
            ->add('name', EntityType::class, [
                'class' => Partner::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingOrganizer::class,
        ]);
    }
}
