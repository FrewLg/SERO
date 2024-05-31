<?php

namespace App\Controller\SERO;

use App\Entity\SERO\MultiSiteCollaboration;
use App\Form\SERO\MultiSiteCollaborationType;
use App\Repository\SERO\MultiSiteCollaborationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/multi/site/collaboration')]
class MultiSiteCollaborationController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_multi_site_collaboration_index', methods: ['GET'])]
    public function index(MultiSiteCollaborationRepository $multiSiteCollaborationRepository): Response
    {
        return $this->render('sero/multi_site_collaboration/index.html.twig', [
            'multi_site_collaborations' => $multiSiteCollaborationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_multi_site_collaboration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $multiSiteCollaboration = new MultiSiteCollaboration();
        $form = $this->createForm(MultiSiteCollaborationType::class, $multiSiteCollaboration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($multiSiteCollaboration);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_multi_site_collaboration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/multi_site_collaboration/new.html.twig', [
            'multi_site_collaboration' => $multiSiteCollaboration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_multi_site_collaboration_show', methods: ['GET'])]
    public function show(MultiSiteCollaboration $multiSiteCollaboration): Response
    {
        return $this->render('sero/multi_site_collaboration/show.html.twig', [
            'multi_site_collaboration' => $multiSiteCollaboration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_multi_site_collaboration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MultiSiteCollaboration $multiSiteCollaboration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MultiSiteCollaborationType::class, $multiSiteCollaboration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_multi_site_collaboration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/multi_site_collaboration/edit.html.twig', [
            'multi_site_collaboration' => $multiSiteCollaboration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_multi_site_collaboration_delete', methods: ['POST'])]
    public function delete(Request $request, MultiSiteCollaboration $multiSiteCollaboration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$multiSiteCollaboration->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($multiSiteCollaboration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_multi_site_collaboration_index', [], Response::HTTP_SEE_OTHER);
    }
}
