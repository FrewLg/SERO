<?php

namespace App\Form;

use App\Entity\Fund;
use App\Entity\Partner;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('budget')
            ->add('currency')

            ->add('amountInWord')
            ->add('fiscalYear')
            ->add('commencedYear')
            ->add('fundEndDate')
            // ->add('updatedAt')
            ->add('partnerOrganization', EntityType::class, [
                'class' => Partner::class,
'choice_label' => 'name',
'label' => 'Funder',
            ])
//             ->add('createdBy', EntityType::class, [
//                 'class' => User::class,
// 'choice_label' => 'id',
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fund::class,
        ]);
    }
}
