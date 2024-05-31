<?php

namespace App\Controller\SERO;

use App\Entity\SERO\InvestigationalNewDrug;
use App\Form\SERO\InvestigationalNewDrugType;
use App\Repository\SERO\InvestigationalNewDrugRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/investigational/new/drug')]
class InvestigationalNewDrugController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_investigational_new_drug_index', methods: ['GET'])]
    public function index(InvestigationalNewDrugRepository $investigationalNewDrugRepository): Response
    {
        return $this->render('sero/investigational_new_drug/index.html.twig', [
            'investigational_new_drugs' => $investigationalNewDrugRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_investigational_new_drug_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $investigationalNewDrug = new InvestigationalNewDrug();
        $form = $this->createForm(InvestigationalNewDrugType::class, $investigationalNewDrug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($investigationalNewDrug);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_investigational_new_drug_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/investigational_new_drug/new.html.twig', [
            'investigational_new_drug' => $investigationalNewDrug,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_investigational_new_drug_show', methods: ['GET'])]
    public function show(InvestigationalNewDrug $investigationalNewDrug): Response
    {
        return $this->render('sero/investigational_new_drug/show.html.twig', [
            'investigational_new_drug' => $investigationalNewDrug,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_investigational_new_drug_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InvestigationalNewDrug $investigationalNewDrug, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvestigationalNewDrugType::class, $investigationalNewDrug);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_investigational_new_drug_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/investigational_new_drug/edit.html.twig', [
            'investigational_new_drug' => $investigationalNewDrug,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_investigational_new_drug_delete', methods: ['POST'])]
    public function delete(Request $request, InvestigationalNewDrug $investigationalNewDrug, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$investigationalNewDrug->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($investigationalNewDrug);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_investigational_new_drug_index', [], Response::HTTP_SEE_OTHER);
    }
}
