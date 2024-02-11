<?php

namespace App\Controller;

use App\Entity\TrainingRequest;
use App\Form\TrainingRequestType;
use App\Repository\TrainingRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/training-request')]
class TrainingRequestController extends AbstractController
{
    #[Route('/', name: 'app_training_request_index', methods: ['GET'])]
    public function index(TrainingRequestRepository $trainingRequestRepository): Response
    {
        return $this->render('training_request/index.html.twig', [
            'training_requests' => $trainingRequestRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_training_request_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingRequest = new TrainingRequest();
        $form = $this->createForm(TrainingRequestType::class, $trainingRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trainingRequest);
            $entityManager->flush();

            return $this->redirectToRoute('app_training_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_request/new.html.twig', [
            'training_request' => $trainingRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_request_show', methods: ['GET'])]
    public function show(TrainingRequest $trainingRequest): Response
    {
        return $this->render('training_request/show.html.twig', [
            'training_request' => $trainingRequest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_request_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingRequest $trainingRequest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingRequestType::class, $trainingRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_training_request_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_request/edit.html.twig', [
            'training_request' => $trainingRequest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_request_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingRequest $trainingRequest, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainingRequest->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trainingRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_request_index', [], Response::HTTP_SEE_OTHER);
    }
}
