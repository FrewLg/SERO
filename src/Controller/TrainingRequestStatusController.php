<?php

namespace App\Controller;

use App\Entity\TrainingRequestStatus;
use App\Form\TrainingRequestStatusType;
use App\Repository\TrainingRequestStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/training/request/status')]
class TrainingRequestStatusController extends AbstractController
{
    #[Route('/', name: 'app_training_request_status_index', methods: ['GET'])]
    public function index(TrainingRequestStatusRepository $trainingRequestStatusRepository): Response
    {
        return $this->render('training_request_status/index.html.twig', [
            'training_request_statuses' => $trainingRequestStatusRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_training_request_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingRequestStatus = new TrainingRequestStatus();
        $form = $this->createForm(TrainingRequestStatusType::class, $trainingRequestStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trainingRequestStatus);
            $entityManager->flush();

            return $this->redirectToRoute('app_training_request_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_request_status/new.html.twig', [
            'training_request_status' => $trainingRequestStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_request_status_show', methods: ['GET'])]
    public function show(TrainingRequestStatus $trainingRequestStatus): Response
    {
        return $this->render('training_request_status/show.html.twig', [
            'training_request_status' => $trainingRequestStatus,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_request_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingRequestStatus $trainingRequestStatus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingRequestStatusType::class, $trainingRequestStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_training_request_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_request_status/edit.html.twig', [
            'training_request_status' => $trainingRequestStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_request_status_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingRequestStatus $trainingRequestStatus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainingRequestStatus->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trainingRequestStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_request_status_index', [], Response::HTTP_SEE_OTHER);
    }
}
