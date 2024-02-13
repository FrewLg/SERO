<?php

namespace App\Controller;

use App\Entity\TrainingParticipant;
use App\Form\TrainingParticipantType;
use App\Repository\TrainingParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/enrollement')]
class TrainingParticipantController extends AbstractController
{
    #[Route('/', name: 'app_training_participant_index', methods: ['GET'])]
    public function index(TrainingParticipantRepository $trainingParticipantRepository): Response
    {
        return $this->render('training_participant/index.html.twig', [
            'training_participants' => $trainingParticipantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_training_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingParticipant = new TrainingParticipant();
        $form = $this->createForm(TrainingParticipantType::class, $trainingParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trainingParticipant);
            $entityManager->flush();

            return $this->redirectToRoute('app_training_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_participant/new.html.twig', [
            'training_participant' => $trainingParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_participant_show', methods: ['GET'])]
    public function show(TrainingParticipant $trainingParticipant): Response
    {
        return $this->render('training_participant/show.html.twig', [
            'training_participant' => $trainingParticipant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingParticipant $trainingParticipant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingParticipantType::class, $trainingParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_training_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_participant/edit.html.twig', [
            'training_participant' => $trainingParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_participant_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingParticipant $trainingParticipant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainingParticipant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trainingParticipant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_participant_index', [], Response::HTTP_SEE_OTHER);
    }
}
