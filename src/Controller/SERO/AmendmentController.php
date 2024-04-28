<?php

namespace App\Controller\SERO;

use App\Entity\SERO\Amendment;
use App\Entity\SERO\Application;
use App\Form\SERO\AmendmentType;
use App\Repository\SERO\AmendmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/amendment')]
class AmendmentController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_amendment_index', methods: ['GET'])]
    public function index(AmendmentRepository $amendmentRepository): Response
    {
        return $this->render('sero/amendment/index.html.twig', [
            'amendments' => $amendmentRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'new_ammendment', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Application $application): Response
    {
        $amendment = new Amendment();
        $form = $this->createForm(AmendmentType::class, $amendment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $amendment->setApplication($application);
            $entityManager->persist($amendment);
            $entityManager->flush();
            $this->addFlash("sucess", "The request sent successfully!");
            return $this->redirectToRoute('application_show', ["id" => $application->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/amendment/new.html.twig', [
            'amendment' => $amendment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_amendment_show', methods: ['GET'])]
    public function show(Amendment $amendment): Response
    {
        return $this->render('sero/amendment/show.html.twig', [
            'amendment' => $amendment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_amendment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Amendment $amendment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AmendmentType::class, $amendment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_amendment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/amendment/edit.html.twig', [
            'amendment' => $amendment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_amendment_delete', methods: ['POST'])]
    public function delete(Request $request, Amendment $amendment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$amendment->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($amendment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_amendment_index', [], Response::HTTP_SEE_OTHER);
    }
}
