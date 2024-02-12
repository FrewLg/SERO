<?php

namespace App\Controller;

use App\Entity\TrainingMaterial;
use App\Form\TrainingMaterialType;
use App\Repository\TrainingMaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/training-material')]
class TrainingMaterialController extends AbstractController
{
    #[Route('/', name: 'app_training_material_index', methods: ['GET'])]
    public function index(TrainingMaterialRepository $trainingMaterialRepository): Response
    {
        return $this->render('training_material/index.html.twig', [
            'training_materials' => $trainingMaterialRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_training_material_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingMaterial = new TrainingMaterial();
        $form = $this->createForm(TrainingMaterialType::class, $trainingMaterial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trainingMaterial);
            $entityManager->flush();

            return $this->redirectToRoute('app_training_material_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_material/new.html.twig', [
            'training_material' => $trainingMaterial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_material_show', methods: ['GET'])]
    public function show(TrainingMaterial $trainingMaterial): Response
    {
        return $this->render('training_material/show.html.twig', [
            'training_material' => $trainingMaterial,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_material_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingMaterial $trainingMaterial, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingMaterialType::class, $trainingMaterial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_training_material_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_material/edit.html.twig', [
            'training_material' => $trainingMaterial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_material_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingMaterial $trainingMaterial, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainingMaterial->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trainingMaterial);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_material_index', [], Response::HTTP_SEE_OTHER);
    }
}
