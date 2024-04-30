<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewersPool;
use App\Form\SERO\ReviewersPoolType;
use App\Repository\SERO\ReviewersPoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/reviewers-pool')]
class ReviewersPoolController extends AbstractController
{
    #[Route('/', name: 'reviewers_pool_index', methods: ['GET'])]
    public function index(ReviewersPoolRepository $reviewersPoolRepository): Response
    {
        return $this->render('sero/reviewers_pool/index.html.twig', [
            'reviewers_pools' => $reviewersPoolRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'reviewers_pool_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewersPool = new ReviewersPool();
        $form = $this->createForm(ReviewersPoolType::class, $reviewersPool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reviewersPool->setDateRegistered(new \DateTime());
            $entityManager->persist($reviewersPool);
            $entityManager->flush();

            return $this->redirectToRoute('reviewers_pool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/reviewers_pool/new.html.twig', [
            'reviewers_pool' => $reviewersPool,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reviewers_pool_show', methods: ['GET'])]
    public function show(ReviewersPool $reviewersPool): Response
    {
        return $this->render('sero/reviewers_pool/show.html.twig', [
            'reviewers_pool' => $reviewersPool,
        ]);
    }

    #[Route('/{id}/edit', name: 'reviewers_pool_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewersPool $reviewersPool, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewersPoolType::class, $reviewersPool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reviewers_pool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/reviewers_pool/edit.html.twig', [
            'reviewers_pool' => $reviewersPool,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reviewers_pool_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewersPool $reviewersPool, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewersPool->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewersPool);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reviewers_pool_index', [], Response::HTTP_SEE_OTHER);
    }
}
