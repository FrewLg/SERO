<?php

namespace App\Controller\SERO;

use App\Entity\SERO\IonizingRadiationUse;
use App\Form\SERO\IonizingRadiationUseType;
use App\Repository\SERO\IonizingRadiationUseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/ionizing/radiation/use')]
class IonizingRadiationUseController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_ionizing_radiation_use_index', methods: ['GET'])]
    public function index(IonizingRadiationUseRepository $ionizingRadiationUseRepository): Response
    {
        return $this->render('sero/ionizing_radiation_use/index.html.twig', [
            'ionizing_radiation_uses' => $ionizingRadiationUseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_ionizing_radiation_use_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ionizingRadiationUse = new IonizingRadiationUse();
        $form = $this->createForm(IonizingRadiationUseType::class, $ionizingRadiationUse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ionizingRadiationUse);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_ionizing_radiation_use_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/ionizing_radiation_use/new.html.twig', [
            'ionizing_radiation_use' => $ionizingRadiationUse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_ionizing_radiation_use_show', methods: ['GET'])]
    public function show(IonizingRadiationUse $ionizingRadiationUse): Response
    {
        return $this->render('sero/ionizing_radiation_use/show.html.twig', [
            'ionizing_radiation_use' => $ionizingRadiationUse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_ionizing_radiation_use_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IonizingRadiationUse $ionizingRadiationUse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IonizingRadiationUseType::class, $ionizingRadiationUse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_ionizing_radiation_use_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/ionizing_radiation_use/edit.html.twig', [
            'ionizing_radiation_use' => $ionizingRadiationUse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_ionizing_radiation_use_delete', methods: ['POST'])]
    public function delete(Request $request, IonizingRadiationUse $ionizingRadiationUse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ionizingRadiationUse->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($ionizingRadiationUse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_ionizing_radiation_use_index', [], Response::HTTP_SEE_OTHER);
    }
}
