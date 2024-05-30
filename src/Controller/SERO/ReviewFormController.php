<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewChecklist;
use App\Entity\SERO\ReviewChecklistGroup;
use App\Entity\SERO\ReviewForm;
use App\Form\SERO\ReviewChecklistGroupType;
use App\Form\SERO\ReviewChecklistType;
use App\Form\SERO\ReviewFormType;
use App\Repository\ReviewChecklistRepository;
use App\Repository\SERO\ReviewChecklistGroupRepository;
use App\Repository\SERO\ReviewFormRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/review-form')]
class ReviewFormController extends AbstractController
{
    #[Route('/', name: 'review_form_index', methods: ['GET','POST'])]
    public function index(Request $request, ReviewFormRepository $reviewFormRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($request->request->get('status-change')) {

            $sf = $reviewFormRepository->findOneBy(['id' => $request->request->get('status-change')]);
            // dd($request->request->get('status-change'));
             
                if ($sf->isActive() == 0) {
                    $sf->setActive(1);
                } else {
                    $sf->setActive(0);
                }
 
            $em->persist($sf);
            $em->flush();

            $this->addFlash("success", "Status has been changed successfully!");
        } 

        return $this->render('sero/review_form/index.html.twig', [
            'review_forms' => $reviewFormRepository->findAll(),
        ]);
    }

    #[Route('/newForm', name: 'app_s_e_r_o_review_form_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewForm = new ReviewForm();
        $form = $this->createForm(ReviewFormType::class, $reviewForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewForm);
            $entityManager->flush();

            return $this->redirectToRoute('review_form_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_form/new.html.twig', [
            'review_form' => $reviewForm,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'review_form_show', methods: ['GET','POST'])]
    public function show(Request $request, ReviewForm $reviewForm,  EntityManagerInterface $entityManager,  ReviewChecklistGroupRepository $reviewChecklistGroupRepository, ReviewChecklistRepository $reviewChecklistRepository): Response
    {

        $reviewChecklist = new ReviewChecklist();
        $form = $this->createForm(ReviewChecklistType::class, $reviewChecklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reviewChecklist->setAnswerType($request->get('answer_type'));
            $reviewChecklist->setReviewForm($reviewForm);
            $entityManager->persist($reviewChecklist);
            $entityManager->flush();

            return $this->redirectToRoute('review_form_show', ['id'=>$reviewForm->getId()], Response::HTTP_SEE_OTHER);
        }

        $reviewChecklistGroup = new ReviewChecklistGroup();
        $reviewChecklistGroupform = $this->createForm(ReviewChecklistGroupType::class, $reviewChecklistGroup);
        $reviewChecklistGroupform->handleRequest($request);

        if ($reviewChecklistGroupform->isSubmitted() && $reviewChecklistGroupform->isValid()) {
            $entityManager->persist($reviewChecklistGroup);
            $entityManager->flush();
            return $this->redirectToRoute('review_form_show', ['id'=>$reviewForm->getId()], Response::HTTP_SEE_OTHER);

        }
        
        return $this->render('sero/review_form/show.html.twig', [
            'reviewForm' => $reviewForm,
            'review_checklists' => $reviewChecklistRepository->findBy(['reviewForm'=>$reviewForm->getId()]),
            'form' => $form,
            'reviewChecklistGroupform'=>$reviewChecklistGroupform,
            'checklist_group' => $reviewChecklistGroupRepository->findAll(),

        ]);
    }

    #[Route('/{id}/editForm', name: 'app_s_e_r_o_review_form_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewForm $reviewForm, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewFormType::class, $reviewForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('review_form_show', ['id'=>$reviewForm->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_form/edit.html.twig', [
            'review_form' => $reviewForm,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}/edit/checklist', name: 'app_s_e_r_o_review_checklist_edit', methods: ['GET', 'POST'])]
    public function editchecklist(Request $request, ReviewChecklist $reviewChecklist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewChecklistType::class, $reviewChecklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('review_form_show', ['id'=>$reviewChecklist->getReviewForm()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_checklist/edit.html.twig', [
            'review_checklist' => $reviewChecklist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_review_form_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewForm $reviewForm, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewForm->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewForm);
            $entityManager->flush();
        }

        return $this->redirectToRoute('review_form_index', [], Response::HTTP_SEE_OTHER);
    }
}
