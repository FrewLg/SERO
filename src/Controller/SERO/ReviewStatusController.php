<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewStatus;
use App\Form\SERO\ReviewStatusType;
use App\Repository\ReviewStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/review-status')]
class ReviewStatusController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_review_status_index', methods: ['GET'])]
    public function index(ReviewStatusRepository $reviewStatusRepository): Response
    {
        return $this->render('sero/review_status/index.html.twig', [
            'review_statuses' => $reviewStatusRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_review_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewStatus = new ReviewStatus();
        $form = $this->createForm(ReviewStatusType::class, $reviewStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewStatus);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_review_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_status/new.html.twig', [
            'review_status' => $reviewStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_review_status_show', methods: ['GET'])]
    public function show(ReviewStatus $reviewStatus): Response
    {
        return $this->render('sero/review_status/show.html.twig', [
            'review_status' => $reviewStatus,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_review_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewStatus $reviewStatus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewStatusType::class, $reviewStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_review_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_status/edit.html.twig', [
            'review_status' => $reviewStatus,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_review_status_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewStatus $reviewStatus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewStatus->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_review_status_index', [], Response::HTTP_SEE_OTHER);
    }
}
