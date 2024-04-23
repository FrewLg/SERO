<?php

namespace App\Controller\SERO;

use App\Entity\SERO\DecisionType;
use App\Form\SERO\DecisionTypeType;
use App\Repository\SERO\DecisionTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/decision-type')]
class DecisionTypeController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_decision_type_index', methods: ['GET'])]
    public function index(DecisionTypeRepository $decisionTypeRepository): Response
    {
        return $this->render('sero/decision_type/index.html.twig', [
            'decision_types' => $decisionTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_decision_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $decisionType = new DecisionType();
        $form = $this->createForm(DecisionTypeType::class, $decisionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($decisionType);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_decision_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/decision_type/new.html.twig', [
            'decision_type' => $decisionType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_decision_type_show', methods: ['GET'])]
    public function show(DecisionType $decisionType): Response
    {
        return $this->render('sero/decision_type/show.html.twig', [
            'decision_type' => $decisionType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_decision_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DecisionType $decisionType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DecisionTypeType::class, $decisionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_decision_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/decision_type/edit.html.twig', [
            'decision_type' => $decisionType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_decision_type_delete', methods: ['POST'])]
    public function delete(Request $request, DecisionType $decisionType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$decisionType->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($decisionType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_decision_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
