<?php

namespace App\Form;

use App\Entity\Modality;
use App\Entity\Room;
use App\Entity\Training;
use App\Entity\TrainingRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//         ->add('TrainingRequest', EntityType::class, [
//             'class' => TrainingRequest::class,
// 'choice_label' => 'trainingTopic',
//         ])
            ->add('modality', EntityType::class, [
                'class' => Modality::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => function (Modality $category): string {
                    return $category->getName();
                }
                            ])
          
            ->add('description')

            ->add('startingDate'
            )
            // , DateType::class, [
            //     'widget' => 'single_text',
             
            //     'attr' => ['class' => 'js-datepicker'],
            // ])
            ->add('endDate'
            )
            // , DateType::class, [
            //     'widget' => 'single_text',
             
            //     'attr' => ['class' => 'js-datepicker'],
            // ])
            ->add('venue', EntityType::class, [
                'class' => Room::class,
'choice_label' => 'name',
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
