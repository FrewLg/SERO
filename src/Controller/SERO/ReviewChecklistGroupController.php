<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewChecklistGroup;
use App\Form\SERO\ReviewChecklistGroupType;
use App\Repository\SERO\ReviewChecklistGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/checklist-group')]
class ReviewChecklistGroupController extends AbstractController
{
    #[Route('/', name: 'review_checklist_group_index', methods: ['GET', 'POST'])]
    public function index(ReviewChecklistGroupRepository $reviewChecklistGroupRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewChecklistGroup = new ReviewChecklistGroup();
        $form = $this->createForm(ReviewChecklistGroupType::class, $reviewChecklistGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewChecklistGroup);
            $entityManager->flush();

            return $this->redirectToRoute('review_checklist_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_checklist_group/index.html.twig', [
            'review_checklist_groups' => $reviewChecklistGroupRepository->findAll(),
            'review_checklist_group' => $reviewChecklistGroup,
            'form' => $form,
        ]);
    }
 

    #[Route('/{id}/edit', name: 'review_checklist_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewChecklistGroup $reviewChecklistGroup, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewChecklistGroupType::class, $reviewChecklistGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('review_checklist_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_checklist_group/edit.html.twig', [
            'review_checklist_group' => $reviewChecklistGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'review_checklist_group_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewChecklistGroup $reviewChecklistGroup, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewChecklistGroup->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewChecklistGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('review_checklist_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
