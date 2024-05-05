<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewStatusGroup;
use App\Form\SERO\ReviewStatusGroupType;
use App\Repository\ReviewStatusGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/review-status-group')]
class ReviewStatusGroupController extends AbstractController
{
    #[Route('/', name: 'review_status_group_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager,ReviewStatusGroupRepository $reviewStatusGroupRepository): Response
    {
        
      
        $reviewStatusGroup = new ReviewStatusGroup();
        $form = $this->createForm(ReviewStatusGroupType::class, $reviewStatusGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewStatusGroup);
            $entityManager->flush();

            return $this->redirectToRoute('review_status_group_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('sero/review_status_group/index.html.twig', [
            'review_status_groups' => $reviewStatusGroupRepository->findAll(),
            'review_status_group' => $reviewStatusGroup,
            'form' => $form,
        ]);
    }

 

    #[Route('/{id}/edit', name: 'review_status_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewStatusGroup $reviewStatusGroup, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewStatusGroupType::class, $reviewStatusGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('review_status_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_status_group/edit.html.twig', [
            'review_status_group' => $reviewStatusGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'review_status_group_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewStatusGroup $reviewStatusGroup, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewStatusGroup->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewStatusGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('review_status_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
