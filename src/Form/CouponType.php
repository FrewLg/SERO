<?php

namespace App\Form;

use App\Entity\Coupon;
use App\Entity\Directorate;
use App\Entity\Training;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('couponNumber')
            // ->add('reservedAt')
            // ->add('createdAt')
            ->add('directorate', EntityType::class, [
                'class' => Directorate::class,
'choice_label' => 'id',
            ])
            ->add('training', EntityType::class, [
                'class' => Training::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coupon::class,
        ]);
    }
}
