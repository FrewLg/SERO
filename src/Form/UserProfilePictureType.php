<?php

namespace App\Form;

use App\Entity\Directorate;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Profile;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfilePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('image', FileType::class, [
                // 'data' => 'abcdef',
                'label' => false,
                'mapped' => false,
                'required' => false,
                "attr" => [
                    "accept" => "image/*",
                    'class' => 'hidden',
                    "class" => "",

                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Profile::class,
        ]);
    }
}
