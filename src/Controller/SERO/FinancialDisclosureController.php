<?php

namespace App\Controller\SERO;

use App\Entity\SERO\FinancialDisclosure;
use App\Form\SERO\FinancialDisclosureType;
use App\Repository\SERO\FinancialDisclosureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/financial/disclosure')]
class FinancialDisclosureController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_financial_disclosure_index', methods: ['GET'])]
    public function index(FinancialDisclosureRepository $financialDisclosureRepository): Response
    {
        return $this->render('sero/financial_disclosure/index.html.twig', [
            'financial_disclosures' => $financialDisclosureRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_financial_disclosure_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $financialDisclosure = new FinancialDisclosure();
        $form = $this->createForm(FinancialDisclosureType::class, $financialDisclosure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($financialDisclosure);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_financial_disclosure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/financial_disclosure/new.html.twig', [
            'financial_disclosure' => $financialDisclosure,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_financial_disclosure_show', methods: ['GET'])]
    public function show(FinancialDisclosure $financialDisclosure): Response
    {
        return $this->render('sero/financial_disclosure/show.html.twig', [
            'financial_disclosure' => $financialDisclosure,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_financial_disclosure_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FinancialDisclosure $financialDisclosure, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FinancialDisclosureType::class, $financialDisclosure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_financial_disclosure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/financial_disclosure/edit.html.twig', [
            'financial_disclosure' => $financialDisclosure,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_financial_disclosure_delete', methods: ['POST'])]
    public function delete(Request $request, FinancialDisclosure $financialDisclosure, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$financialDisclosure->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($financialDisclosure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_financial_disclosure_index', [], Response::HTTP_SEE_OTHER);
    }
}
