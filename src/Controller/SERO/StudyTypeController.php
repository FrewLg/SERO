<?php

namespace App\Controller\SERO;

use App\Entity\SERO\StudyType;
use App\Form\SERO\StudyTypeType;
use App\Repository\SERO\StudyTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/study-type')]
class StudyTypeController extends AbstractController
{
    #[Route('/', name: 'study_type_index', methods: ['GET','POST'])]
    public function index(StudyTypeRepository $studyTypeRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $studyType = new StudyType();
        $form = $this->createForm(StudyTypeType::class, $studyType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($studyType);
            $entityManager->flush();

            return $this->redirectToRoute('study_type_index', [], Response::HTTP_SEE_OTHER);
        }

    
        return $this->render('sero/study_type/index.html.twig', [
            'study_types' => $studyTypeRepository->findAll(),
            'study_type' => $studyType,
            'form' => $form,
        ]);
    }
 

  
    #[Route('/{id}/edit', name: 'study_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StudyType $studyType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StudyTypeType::class, $studyType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('study_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/study_type/edit.html.twig', [
            'study_type' => $studyType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'study_type_delete', methods: ['POST'])]
    public function delete(Request $request, StudyType $studyType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$studyType->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($studyType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('study_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
