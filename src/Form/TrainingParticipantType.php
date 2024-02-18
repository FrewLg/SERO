<?php

namespace App\Form;

use App\Entity\Training;
use App\Entity\TrainingParticipant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrainingParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('voucher')
            ->add('attended')
            ->add('certified')
            ->add('registeredAt')
            ->add('certId')
            ->add('certIssuedAt')
            ->add('training', EntityType::class, [
                'class' => Training::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrainingParticipant::class,
        ]);
    }
}
