<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewType;
use App\Form\SERO\ReviewTypeType;
use App\Repository\SERO\ReviewTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/review-type')]
class ReviewTypeController extends AbstractController
{
    #[Route('/', name: 'review_type_index', methods: ['GET','POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ReviewTypeRepository $reviewTypeRepository): Response
    { 
        $reviewType = new ReviewType();
        $form = $this->createForm(ReviewTypeType::class, $reviewType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewType);
            $entityManager->flush();

            return $this->redirectToRoute('review_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_type/index.html.twig', [
            'review_type' => $reviewType,
            'review_types' => $reviewTypeRepository->findAll(),
            'form' => $form,
        ]);
    }
 

    #[Route('/{id}/edit', name: 'review_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewType $reviewType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewTypeType::class, $reviewType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('review_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_type/edit.html.twig', [
            'review_type' => $reviewType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'review_type_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewType $reviewType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewType->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('review_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
