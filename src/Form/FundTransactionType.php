<?php

namespace App\Form;

use App\Entity\Fund;
use App\Entity\FundTransaction;
use App\Entity\Training;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FundTransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deducted')
            // ->add('currentBalance')
            // ->add('deductedAt')
            ->add('reason')
            ->add('name')
            // ->add('referenceNubmer')
            ->add('fundName', EntityType::class, [
                'class' => Fund::class,
'choice_label' => 'name',
            ])
            ->add('allotedTo', EntityType::class, [
                'class' => Training::class,
// 'choice_label' => 'training_request',
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
            'data_class' => FundTransaction::class,
        ]);
    }
}
