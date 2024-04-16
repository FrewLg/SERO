<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ApplicationFeedback;
use App\Form\SERO\ApplicationFeedbackType;
use App\Repository\SERO\ApplicationFeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/application/feedback')]
class ApplicationFeedbackController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_application_feedback_index', methods: ['GET'])]
    public function index(ApplicationFeedbackRepository $applicationFeedbackRepository): Response
    {
        return $this->render('sero/application_feedback/index.html.twig', [
            'application_feedbacks' => $applicationFeedbackRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_application_feedback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $applicationFeedback = new ApplicationFeedback();
        $form = $this->createForm(ApplicationFeedbackType::class, $applicationFeedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($applicationFeedback);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_application_feedback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/application_feedback/new.html.twig', [
            'application_feedback' => $applicationFeedback,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_application_feedback_show', methods: ['GET'])]
    public function show(ApplicationFeedback $applicationFeedback): Response
    {
        return $this->render('sero/application_feedback/show.html.twig', [
            'application_feedback' => $applicationFeedback,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_application_feedback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ApplicationFeedback $applicationFeedback, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ApplicationFeedbackType::class, $applicationFeedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_application_feedback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/application_feedback/edit.html.twig', [
            'application_feedback' => $applicationFeedback,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_application_feedback_delete', methods: ['POST'])]
    public function delete(Request $request, ApplicationFeedback $applicationFeedback, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$applicationFeedback->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($applicationFeedback);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_application_feedback_index', [], Response::HTTP_SEE_OTHER);
    }
}
