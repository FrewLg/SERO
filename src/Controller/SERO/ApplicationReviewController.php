<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ApplicationReview;
use App\Form\SERO\ApplicationReviewType;
use App\Repository\ApplicationReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/application/review')]
class ApplicationReviewController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_application_review_index', methods: ['GET'])]
    public function index(ApplicationReviewRepository $applicationReviewRepository): Response
    {
        return $this->render('sero/application_review/index.html.twig', [
            'application_reviews' => $applicationReviewRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_application_review_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $applicationReview = new ApplicationReview();
        $form = $this->createForm(ApplicationReviewType::class, $applicationReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($applicationReview);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_application_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/application_review/new.html.twig', [
            'application_review' => $applicationReview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_application_review_show', methods: ['GET'])]
    public function show(ApplicationReview $applicationReview): Response
    {
        return $this->render('sero/application_review/show.html.twig', [
            'application_review' => $applicationReview,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_application_review_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ApplicationReview $applicationReview, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ApplicationReviewType::class, $applicationReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_application_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/application_review/edit.html.twig', [
            'application_review' => $applicationReview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_application_review_delete', methods: ['POST'])]
    public function delete(Request $request, ApplicationReview $applicationReview, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$applicationReview->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($applicationReview);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_application_review_index', [], Response::HTTP_SEE_OTHER);
    }
}
