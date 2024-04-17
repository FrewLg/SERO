<?php

namespace App\Form;

use App\Entity\Directorate;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                    'class'=>'form-control   ',
                    'required' => false,
                ]])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                    'class'=>'form-control   ',
                    'required' => false,
                ]])
            ->add('alternativeEmail', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                    'class'=>'form-control   ',
                    'required' => false,
                ]])
            ->add('department', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                    'class'=>'form-control   ',
                    'required' => false,
                ]])
            ->add('phoneNumber', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                    'class'=>'form-control   ',
                    'required' => false,
                ]])
            ->add('birthDate', DateType::class, array(
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'required' => true,
                    'class' => 'form-control',
                ),
            ))
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                    'class'=>'form-control   ',
                    'required' => false,
                ]])
            // ->add('nationalID')
            ->add('signature', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name',
                    'class'=>'form-control   ',
                    'required' => false,
                ]])
           
            ->add('dirctorate', EntityType::class, [
                'class' => Directorate::class,
'choice_label' => 'name',
'attr' => [
    'placeholder' => 'Name',
    'class'=>'form-control  select2 ',
    'required' => false,
]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}

  
 
class UserProfilePictureType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder

            ->add('image', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                "attr" => [
                    "accept" => "image/*",
                    "class" => "form-control form-group",

                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}