<?php

namespace App\Controller\SERO;

use App\Entity\SERO\SpecialResRequirement;
use App\Form\SERO\SpecialResRequirementType;
use App\Repository\SERO\SpecialResRequirementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/special/res/requirement')]
class SpecialResRequirementController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_special_res_requirement_index', methods: ['GET'])]
    public function index(SpecialResRequirementRepository $specialResRequirementRepository): Response
    {
        return $this->render('sero/special_res_requirement/index.html.twig', [
            'special_res_requirements' => $specialResRequirementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_special_res_requirement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $specialResRequirement = new SpecialResRequirement();
        $form = $this->createForm(SpecialResRequirementType::class, $specialResRequirement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($specialResRequirement);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_special_res_requirement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/special_res_requirement/new.html.twig', [
            'special_res_requirement' => $specialResRequirement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_special_res_requirement_show', methods: ['GET'])]
    public function show(SpecialResRequirement $specialResRequirement): Response
    {
        return $this->render('sero/special_res_requirement/show.html.twig', [
            'special_res_requirement' => $specialResRequirement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_special_res_requirement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SpecialResRequirement $specialResRequirement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpecialResRequirementType::class, $specialResRequirement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_special_res_requirement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/special_res_requirement/edit.html.twig', [
            'special_res_requirement' => $specialResRequirement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_special_res_requirement_delete', methods: ['POST'])]
    public function delete(Request $request, SpecialResRequirement $specialResRequirement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specialResRequirement->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($specialResRequirement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_special_res_requirement_index', [], Response::HTTP_SEE_OTHER);
    }
}
