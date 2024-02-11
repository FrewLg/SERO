<?php

namespace App\Controller;

use App\Entity\TrainingOrganizer;
use App\Entity\Training;
use App\Form\TrainingOrganizerType;
use App\Repository\TrainingOrganizerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/organizer')]
class TrainingOrganizerController extends AbstractController
{
    #[Route('/', name: 'app_training_organizer_index', methods: ['GET'])]
    public function index(TrainingOrganizerRepository $trainingOrganizerRepository): Response
    {
        return $this->render('training_organizer/index.html.twig', [
            'training_organizers' => $trainingOrganizerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_training_organizer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingOrganizer = new TrainingOrganizer();
        $form = $this->createForm(TrainingOrganizerType::class, $trainingOrganizer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trainingOrganizer);
            $entityManager->flush();

            return $this->redirectToRoute('app_training_organizer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_organizer/new.html.twig', [
            'training_organizer' => $trainingOrganizer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_organizer_show', methods: ['GET'])]
    public function show(TrainingOrganizer $trainingOrganizer): Response
    {
        return $this->render('training_organizer/show.html.twig', [
            'training_organizer' => $trainingOrganizer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_organizer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingOrganizer $trainingOrganizer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingOrganizerType::class, $trainingOrganizer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_training_organizer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_organizer/edit.html.twig', [
            'training_organizer' => $trainingOrganizer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_organizer_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingOrganizer $trainingOrganizer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainingOrganizer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trainingOrganizer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_organizer_index', [], Response::HTTP_SEE_OTHER);
    }
}
