<?php

namespace App\Form\SERO;

use App\Entity\SERO\BoardMember;
use App\Entity\SERO\Meeting;
use App\Entity\SERO\MeetingSchedule;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('heldAt', null, [
                'widget' => 'single_text',
            ])
            ->add('status')
            ->add('meetingSchedule', EntityType::class, [
                'class' => MeetingSchedule::class,
                'choice_label' => 'name',
            ])
            ->add('note')
            ->add('minuteTakenAt', null, [
                'widget' => 'single_text',
            ])
            ->add('createdBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('attendee', EntityType::class, [
                'class' => BoardMember::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('minuteTakenBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meeting::class,
        ]);
    }
}
