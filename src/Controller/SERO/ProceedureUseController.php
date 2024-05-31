<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ProceedureUse;
use App\Form\SERO\ProceedureUseType;
use App\Repository\SERO\ProceedureUseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/proceedure/use')]
class ProceedureUseController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_proceedure_use_index', methods: ['GET'])]
    public function index(ProceedureUseRepository $proceedureUseRepository): Response
    {
        return $this->render('sero/proceedure_use/index.html.twig', [
            'proceedure_uses' => $proceedureUseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_proceedure_use_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $proceedureUse = new ProceedureUse();
        $form = $this->createForm(ProceedureUseType::class, $proceedureUse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($proceedureUse);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_proceedure_use_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/proceedure_use/new.html.twig', [
            'proceedure_use' => $proceedureUse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_proceedure_use_show', methods: ['GET'])]
    public function show(ProceedureUse $proceedureUse): Response
    {
        return $this->render('sero/proceedure_use/show.html.twig', [
            'proceedure_use' => $proceedureUse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_proceedure_use_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProceedureUse $proceedureUse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProceedureUseType::class, $proceedureUse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_proceedure_use_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/proceedure_use/edit.html.twig', [
            'proceedure_use' => $proceedureUse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_proceedure_use_delete', methods: ['POST'])]
    public function delete(Request $request, ProceedureUse $proceedureUse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proceedureUse->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($proceedureUse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_proceedure_use_index', [], Response::HTTP_SEE_OTHER);
    }
}
