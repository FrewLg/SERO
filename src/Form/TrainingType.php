<?php

namespace App\Form;

use App\Entity\Room;
use App\Entity\Training;
use App\Entity\TrainingRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('startingDate')
            ->add('endDate')
            ->add('description')
            ->add('createdAt')
            // ->add('updatedAt')
            ->add('TrainingRequest', EntityType::class, [
                'class' => TrainingRequest::class,
'choice_label' => 'id',
            ])
            ->add('venue', EntityType::class, [
                'class' => Room::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Training::class,
        ]);
    }
}
