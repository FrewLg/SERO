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
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProtocolFlowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('startDate', null, [
                'widget' => 'single_text',
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
            ])
            ->add('location')
            ->add('description')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('ibcode')
            ->add('requestedExclusionParticipant')
            ->add('status')
            ->add('attachment')
            ->add('submittedBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('studyType', EntityType::class, [
                'class' => StudyType::class,
                'choice_label' => 'id',
            ])
            ->add('studyPopulation', EntityType::class, [
                'class' => StudyPopulation::class,
                'choice_label' => 'id',
            ])
            ->add('participantCharacter', EntityType::class, [
                'class' => ParticipantCharacter::class,
                'choice_label' => 'id',
            ])
            ->add('specialResourceRequirement', EntityType::class, [
                'class' => SpecialResRequirement::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('ionizingRadiationUse', EntityType::class, [
                'class' => IonizingRadiationUse::class,
                'choice_label' => 'id',
            ])
            ->add('investigationalNewDrug', EntityType::class, [
                'class' => InvestigationalNewDrug::class,
                'choice_label' => 'id',
            ])
            ->add('proceedureUse', EntityType::class, [
                'class' => ProceedureUse::class,
                'choice_label' => 'id',
            ])
            ->add('multiSiteCollaboration', EntityType::class, [
                'class' => MultiSiteCollaboration::class,
                'choice_label' => 'id',
            ])
            ->add('financialDisclosure', EntityType::class, [
                'class' => FinancialDisclosure::class,
                'choice_label' => 'id',
            ])
            ->add('attachmentType', EntityType::class, [
                'class' => AttachmentType::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
