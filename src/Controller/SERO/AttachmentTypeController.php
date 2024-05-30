<?php

namespace App\Controller\SERO;

use App\Entity\SERO\AttachmentType;
use App\Form\SERO\AttachmentTypeType;
use App\Repository\SERO\AttachmentTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/attachment/type')]
class AttachmentTypeController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_attachment_type_index', methods: ['GET'])]
    public function index(AttachmentTypeRepository $attachmentTypeRepository): Response
    {
        return $this->render('sero/attachment_type/index.html.twig', [
            'attachment_types' => $attachmentTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_attachment_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attachmentType = new AttachmentType();
        $form = $this->createForm(AttachmentTypeType::class, $attachmentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attachmentType);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_attachment_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/attachment_type/new.html.twig', [
            'attachment_type' => $attachmentType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_attachment_type_show', methods: ['GET'])]
    public function show(AttachmentType $attachmentType): Response
    {
        return $this->render('sero/attachment_type/show.html.twig', [
            'attachment_type' => $attachmentType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_attachment_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AttachmentType $attachmentType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttachmentTypeType::class, $attachmentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_attachment_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/attachment_type/edit.html.twig', [
            'attachment_type' => $attachmentType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_attachment_type_delete', methods: ['POST'])]
    public function delete(Request $request, AttachmentType $attachmentType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attachmentType->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($attachmentType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_attachment_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
