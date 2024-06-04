<?php

namespace App\Form\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\AttachmentType;
use App\Entity\SERO\FinancialDisclosure;
use App\Entity\SERO\InvestigationalNewDrug;
use App\Entity\SERO\IonizingRadiationUse;
use App\Entity\SERO\MultiSiteCollaboration;
use App\Entity\SERO\ParticipantCharacter;
use App\Entity\SERO\ProceedureUse;
use App\Entity\SERO\SpecialResRequirement;
use App\Entity\SERO\StudyPopulation;
use App\Entity\SERO\StudyType;
use PHPUnit\Framework\Test;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Protocol title'

                ]
            ])
            ->add('startDate', null, [
                'widget' => 'single_text',
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
            ])
            ->add('location', TextType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Location'

                ]
            ])

            ->add('studyType', EntityType::class, [
                'label' => 'studyType',
                'placeholder' => '-- Select studyType --',
                'class' => StudyType::class,
                'attr' => [
                    'class' => '  form-control form-control-lg select2 form-control-solid',
                ],
                'required' => false,

                'choice_label' => 'name',
            ])
            ->add('studyPopulation', EntityType::class, [
                'label' => 'studyPopulation',
                'placeholder' => '-- Select studyPopulation --',
                'class' => StudyPopulation::class,
                'attr' => [
                    'class' => '  form-control form-control-lg select2 form-control-solid',
                ],
                'required' => false,

                'choice_label' => 'name',
            ])
            ->add('participantCharacter', EntityType::class, [
                'label' => 'participantCharacter',
                'placeholder' => '-- Select participantCharacter --',
                'class' => ParticipantCharacter::class,
                'attr' => [
                    'class' => '  form-control form-control-lg select2 form-control-solid',
                ],
                'required' => false,

                'choice_label' => 'name',
            ])
            ->add('specialResourceRequirement', EntityType::class, [
                'label' => 'specialResourceRequirement',
                'placeholder' => '-- Select specialResourceRequirement   --',
                'class' => SpecialResRequirement::class,
                'attr' => [
                    'class' => '  form-control select2 ',
                ],
                'required' => false,
                'multiple' => true,

                'choice_label' => 'name',
            ])
            ->add('ionizingRadiationUse', EntityType::class, [
                'label' => 'ionizingRadiationUse',
                'placeholder' => '-- Select ionizingRadiationUse --',
                'class' => IonizingRadiationUse::class,
                'attr' => [
                    'class' => '  form-control form-control-lg form-control-solid',
                ],
                'required' => false,

                'choice_label' => 'name',
            ])
            ->add('investigationalNewDrug', EntityType::class, [
                'label' => 'investigationalNewDrug',
                'placeholder' => '-- Select investigationalNewDrug --',
                'class' => InvestigationalNewDrug::class,
                'attr' => [
                    'class' => '  form-control form-control-lg form-control-solid',
                ],
                'required' => false,

                'choice_label' => 'name',
            ])
            ->add('multiSiteCollaboration', EntityType::class, [
                'label' => 'multiSiteCollaboration',
                'placeholder' => '-- Select multiSiteCollaboration --',
                'class' => MultiSiteCollaboration::class,
                'attr' => [
                    'class' => '  form-control form-control-lg form-control-solid',
                ],
                'required' => false,

                'choice_label' => 'name',
            ])
            ->add('financialDisclosure', EntityType::class, [
                'label' => 'financialDisclosure',
                'placeholder' => '-- Select financialDisclosure --',
                'class' => FinancialDisclosure::class,
                'attr' => [
                    'class' => '  form-control form-control-lg form-control-solid',
                ],
                'required' => false,

                'choice_label' => 'name',
            ])
            ->add('proceedureUse', EntityType::class, [
                'label' => 'proceedureUse',
                'placeholder' => '-- Select proceedureUse --',
                'class' => ProceedureUse::class,
                'attr' => [
                    'class' => '  form-control form-control-lg form-control-solid',
                ],
                'required' => false,

                'choice_label' => 'name',
            ])

            ->add('participatingInvestigators', CollectionType::class, [
                'entry_type' => ParticipatingInvestigatorType::class,
                'entry_options' =>
                [
                    'label' => false
                ],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                // 'data_class'=>false,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control', 'placeholder' => 'Descrctiption'

                ]
            ])
            ->add('attachment', FileType::class, [
                'label' => 'Upload attachment',
                // 'mapped' => false,
                'attr' => [
                    'class' => 'form-control my-4',
                ],
                'required' => true,
            ])
            ->add('attachmentType', EntityType::class, [
                'label' => 'Attachment type',
                'placeholder' => '-- Select Attachment type --',
                'class' => AttachmentType::class,
                'attr' => [
                    'class' => '  form-control form-control-lg form-control-solid',
                ],
                // 'mapped' => false,
                'required' => false,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
