<?php

namespace App\Controller;

use App\Entity\Directorate;
use App\Form\DirectorateType;
use App\Repository\DirectorateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/directorate')]
class DirectorateController extends AbstractController
{
    #[Route('/', name: 'app_directorate_index', methods: ['GET'])]
    public function index(DirectorateRepository $directorateRepository): Response
    {
       
        return $this->render('directorate/index.html.twig', [
            'directorates' => $directorateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_directorate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $directorate = new Directorate();
        $form = $this->createForm(DirectorateType::class, $directorate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             
            $directorate->setCreatedAt(new \Datetime());

            $entityManager->persist($directorate);
            $entityManager->flush();

            return $this->redirectToRoute('app_directorate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('directorate/new.html.twig', [
            'directorate' => $directorate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_directorate_show', methods: ['GET'])]
    public function show(Directorate $directorate): Response
    {
        return $this->render('directorate/show.html.twig', [
            'directorate' => $directorate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_directorate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Directorate $directorate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DirectorateType::class, $directorate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $directorate->setUpdatedAt(new \Datetime());

            $entityManager->flush();

            return $this->redirectToRoute('app_directorate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('directorate/edit.html.twig', [
            'directorate' => $directorate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_directorate_delete', methods: ['POST'])]
    public function delete(Request $request, Directorate $directorate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$directorate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($directorate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_directorate_index', [], Response::HTTP_SEE_OTHER);
    }
}
