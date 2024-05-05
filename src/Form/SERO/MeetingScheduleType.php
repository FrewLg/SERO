<?php

namespace App\Form\SERO;

use App\Entity\SERO\MeetingSchedule;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingScheduleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                // 'class' => ReviewForm::class,
                'attr' => [
                'placeholder' => 'Schedule name',
                'class' =>'form-control form-control-lg form-control-solid    mb-4 p-4'],
            ])
            ->add('startingDate'
            , DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'label' => 'Meeting Starting date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => (new DateTime('+1 day')),
                'attr' => array(
                    'min' => (new DateTime())->format('Y-m-d'),
                    'max' => (new DateTime('+365 day'))->format('Y-m-d'),
                    'required' => true,
                    'class' => 'form-control',
                ),
            ))
            ->add('endDate', DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'label' => 'Meeting Ending Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => (new DateTime('+1 day')),
                'attr' => array(
                    'min' => (new DateTime())->format('Y-m-d'),
                    'max' => (new DateTime('+365 day'))->format('Y-m-d'),
                    'required' => true,
                    'class' => 'form-control',
                ),
            ))
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MeetingSchedule::class,
        ]);
    }
}
