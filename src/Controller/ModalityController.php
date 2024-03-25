<?php

namespace App\Controller;

use App\Entity\Modality;
use App\Form\ModalityType;
use App\Repository\ModalityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/modality')]
class ModalityController extends AbstractController
{
    #[Route('/', name: 'app_modality_index', methods: ['GET'])]
    public function index(ModalityRepository $modalityRepository, Request $request): Response
    {
        $locale = $request->getLocale();

        return $this->render('modality/index.html.twig', [
            'modalities' => $modalityRepository->findAll(),
            'locale'=>$locale
        ]);
    }

    #[Route('/new', name: 'app_modality_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $modality = new Modality();
        $form = $this->createForm(ModalityType::class, $modality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modality->setCreatedAt(new \Datetime());

            
            $this->addFlash('success','Details added successflly!');

            $entityManager->persist($modality);
            $entityManager->flush();

            return $this->redirectToRoute('app_modality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modality/new.html.twig', [
            'modality' => $modality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_modality_show', methods: ['GET'])]
    public function show(Modality $modality): Response
    {
        return $this->render('modality/show.html.twig', [
            'modality' => $modality,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_modality_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Modality $modality, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModalityType::class, $modality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_modality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('modality/edit.html.twig', [
            'modality' => $modality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_modality_delete', methods: ['POST'])]
    public function delete(Request $request, Modality $modality, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modality->getId(), $request->request->get('_token'))) {
            $entityManager->remove($modality);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_modality_index', [], Response::HTTP_SEE_OTHER);
    }
}
