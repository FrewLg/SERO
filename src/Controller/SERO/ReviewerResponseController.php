<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewerResponse;
use App\Form\SERO\ReviewerResponseType;
use App\Repository\SERO\ReviewerResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/reviewer-response')]
class ReviewerResponseController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_reviewer_response_index', methods: ['GET'])]
    public function index(ReviewerResponseRepository $reviewerResponseRepository): Response
    {
        return $this->render('sero/reviewer_response/index.html.twig', [
            'reviewer_responses' => $reviewerResponseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_reviewer_response_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewerResponse = new ReviewerResponse();
        $form = $this->createForm(ReviewerResponseType::class, $reviewerResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewerResponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_reviewer_response_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/reviewer_response/new.html.twig', [
            'reviewer_response' => $reviewerResponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_reviewer_response_show', methods: ['GET'])]
    public function show(ReviewerResponse $reviewerResponse): Response
    {
        return $this->render('sero/reviewer_response/show.html.twig', [
            'reviewer_response' => $reviewerResponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_reviewer_response_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewerResponse $reviewerResponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewerResponseType::class, $reviewerResponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_reviewer_response_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/reviewer_response/edit.html.twig', [
            'reviewer_response' => $reviewerResponse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_reviewer_response_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewerResponse $reviewerResponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewerResponse->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewerResponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_reviewer_response_index', [], Response::HTTP_SEE_OTHER);
    }
}
