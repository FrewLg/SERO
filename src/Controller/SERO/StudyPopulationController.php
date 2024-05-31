<?php

namespace App\Controller\SERO;

use App\Entity\SERO\StudyPopulation;
use App\Form\SERO\StudyPopulationType;
use App\Repository\SERO\StudyPopulationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/study/population')]
class StudyPopulationController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_study_population_index', methods: ['GET'])]
    public function index(StudyPopulationRepository $studyPopulationRepository): Response
    {
        return $this->render('sero/study_population/index.html.twig', [
            'study_populations' => $studyPopulationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_study_population_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $studyPopulation = new StudyPopulation();
        $form = $this->createForm(StudyPopulationType::class, $studyPopulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($studyPopulation);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_study_population_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/study_population/new.html.twig', [
            'study_population' => $studyPopulation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_study_population_show', methods: ['GET'])]
    public function show(StudyPopulation $studyPopulation): Response
    {
        return $this->render('sero/study_population/show.html.twig', [
            'study_population' => $studyPopulation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_study_population_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StudyPopulation $studyPopulation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StudyPopulationType::class, $studyPopulation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_study_population_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/study_population/edit.html.twig', [
            'study_population' => $studyPopulation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_study_population_delete', methods: ['POST'])]
    public function delete(Request $request, StudyPopulation $studyPopulation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studyPopulation->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($studyPopulation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_study_population_index', [], Response::HTTP_SEE_OTHER);
    }
}
