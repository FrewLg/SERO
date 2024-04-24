<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\BoardMember;
use App\Entity\SERO\Meeting;
use App\Entity\SERO\MeetingSchedule;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class,[
                'attr'=> ['class'=>'form-control  multiple col-6']

            ])
            ->add('heldAt', null, [
                'widget' => 'single_text',
                'attr'=> ['class'=>'form-control  multiple col-6']

            ])
            // ->add('status')
            
            ->add('note', TextareaType::class,[
                'attr'=> ['class'=>'form-control  multiple col-6']

            ])
            ->add('minuteTakenAt', null, [
                'widget' => 'single_text',
                'attr'=> ['class'=>'form-control  multiple col-6']

            ])
             
            ->add('attendee', EntityType::class, [
                'class' => BoardMember::class,
                'label' => 'Attendee:',
                'choice_label' => 'user',
                'attr'=> ['class'=>'select2 col-6'],
                'multiple' => true,
            ])
            ->add('applications', EntityType::class, [
                'class' => Application::class,
                'choice_label' => 'title',
                'multiple' => true,
                'attr'=> ['class'=>'select2 multiple col-6']
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
