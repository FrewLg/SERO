<?php

namespace App\Controller;

use App\Entity\TrainingTopic;
use App\Form\TrainingTopicType;
use App\Repository\TrainingTopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/training-topic')]
class TrainingTopicController extends AbstractController
{
    #[Route('/', name: 'app_training_topic_index', methods: ['GET'])]
    public function index(TrainingTopicRepository $trainingTopicRepository): Response
    {
        return $this->render('training_topic/index.html.twig', [
            'training_topics' => $trainingTopicRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_training_topic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingTopic = new TrainingTopic();
        $form = $this->createForm(TrainingTopicType::class, $trainingTopic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trainingTopic->setCreatedAt(new \Datetime());

            $entityManager->persist($trainingTopic);
            $entityManager->flush();

            return $this->redirectToRoute('app_training_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_topic/new.html.twig', [
            'training_topic' => $trainingTopic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_topic_show', methods: ['GET'])]
    public function show(TrainingTopic $trainingTopic): Response
    {
        return $this->render('training_topic/show.html.twig', [
            'training_topic' => $trainingTopic,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_topic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingTopic $trainingTopic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingTopicType::class, $trainingTopic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trainingTopic->setUpdatedAt(new \Datetime());

            $entityManager->flush();

            return $this->redirectToRoute('app_training_topic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_topic/edit.html.twig', [
            'training_topic' => $trainingTopic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_topic_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingTopic $trainingTopic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainingTopic->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trainingTopic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_topic_index', [], Response::HTTP_SEE_OTHER);
    }
}
