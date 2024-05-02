<?php

namespace App\Form\SERO;

use App\Entity\SERO\Meeting;
use App\Entity\SERO\ScheduledProtocol;
use App\Entity\SERO\Version;
use App\Repository\SERO\VersionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersionForMeetingType extends AbstractType
{
    private $versionRepository;
    public function __construct(VersionRepository $versionRepository)
    {
        $this->versionRepository = $versionRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('protocol', EntityType::class, [
            //     'class' => Version::class,
            //     'choice_label' => 'id',
            // ])

            ->add('protocol', EntityType::class, [
                'class' => Version::class,
                'choice_label' => function(Version $version) {
                    return $version->getApplication();
                },
                'placeholder' => 'Choose a protocol',
                // 'multiple' => true,
                'label' => "Protocol",
                 'attr' => ['class' => 'form-control select2 col-11'],

                'choices' => $this->versionRepository->findBy(['status'=>1]),
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ScheduledProtocol::class,
        ]);
    }
}
