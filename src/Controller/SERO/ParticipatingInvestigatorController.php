<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ParticipatingInvestigator;
use App\Form\SERO\ParticipatingInvestigatorType;
use App\Repository\SERO\ParticipatingInvestigatorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/participating/investigator')]
class ParticipatingInvestigatorController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_participating_investigator_index', methods: ['GET'])]
    public function index(ParticipatingInvestigatorRepository $participatingInvestigatorRepository): Response
    {
        return $this->render('sero/participating_investigator/index.html.twig', [
            'participating_investigators' => $participatingInvestigatorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_participating_investigator_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participatingInvestigator = new ParticipatingInvestigator();
        $form = $this->createForm(ParticipatingInvestigatorType::class, $participatingInvestigator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participatingInvestigator);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_participating_investigator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/participating_investigator/new.html.twig', [
            'participating_investigator' => $participatingInvestigator,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_participating_investigator_show', methods: ['GET'])]
    public function show(ParticipatingInvestigator $participatingInvestigator): Response
    {
        return $this->render('sero/participating_investigator/show.html.twig', [
            'participating_investigator' => $participatingInvestigator,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_participating_investigator_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParticipatingInvestigator $participatingInvestigator, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipatingInvestigatorType::class, $participatingInvestigator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_participating_investigator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/participating_investigator/edit.html.twig', [
            'participating_investigator' => $participatingInvestigator,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_participating_investigator_delete', methods: ['POST'])]
    public function delete(Request $request, ParticipatingInvestigator $participatingInvestigator, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participatingInvestigator->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($participatingInvestigator);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_participating_investigator_index', [], Response::HTTP_SEE_OTHER);
    }
}
