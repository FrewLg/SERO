<?php

namespace App\Form;

use App\Entity\Facility;
use App\Entity\Partner;
use App\Entity\TrainingRequest;
use App\Entity\TrainingTopic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('trainingName')
            
            ->add('programDetails')
            ->add('facility', EntityType::class, [
                'class' => Facility::class,
                'choice_label' => 'name',
                'multiple' => true,
                            ]) 
            ->add('trainingTopic', EntityType::class, [
            'class' => TrainingTopic::class,
            'choice_label' => 'name',
            // 'multiple' => true,
            ]) 
            ->add('organizer', EntityType::class, [
            'class' => Partner::class,
            'choice_label' => 'name',
            'multiple' => true,
                        ])
            ->add('numberOfParticipants')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingRequest::class,
        ]);
    }
}
