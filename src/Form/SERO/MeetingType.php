<?php

namespace App\Form\SERO;

use App\Entity\SERO\BoardMember;
use App\Entity\SERO\Meeting;
use App\Repository\SERO\VersionRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingType extends AbstractType
{

    private $versionRepository;
    public function __construct(VersionRepository $versionRepository)
    {
        $this->versionRepository = $versionRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'attr' => ['class' => 'form-control multiple col-9']

            ])
            ->add('heldAt', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control multiple col-9']
            ])
            ->add('scheduledProtocols', CollectionType::class, [
                'entry_type' => VersionForMeetingType::class,
                // 'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('note', TextareaType::class, [
                'attr' => ['class' => 'form-control multiple col-9']
            ])
            ->add('minuteTakenAt', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control multiple col-9']
            ])
            ->add('attendee', EntityType::class, [
                'class' => BoardMember::class,
                'label' => 'Attendee:',
                'choice_label' => 'user',
                'attr' => ['class' => 'form-control multiple select2 col-9'],
                'multiple' => true,
            ])

            // ->add('versions', null, array(

            //     'placeholder' => '---Select funding scheme  ---',
            //     "class" => Version::class,
            //     'attr' => array(
            //         'empty' => 'Version', 
            //         'class' => 'select2 chosen-select form-control',
            //     ),
            // ))
            // ->add('versions', EntityType::class, [
            //     'class' => Version::class,
            //     'choice_label' => function(Version $version) {
            //         // return sprintf('(%d) %s', $version->getVersionNumber(), $version->getApplication())
            //         return $version->getApplication();
            //     },
            //     'placeholder' => 'Choose a protocol',
            //     'multiple' => true,
            //     'label' => "Protocol",
            //     'attr'=> ['class'=>'select2 multiple form-control-solid py-4 m-8 col-9'],
            //     'choices' => $this->versionRepository->findBy(['status'=>1]),
            // ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meeting::class,
        ]);
    }
}
