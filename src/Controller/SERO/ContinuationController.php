<?php

namespace App\Controller\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\Continuation;
use App\Form\SERO\ContinuationType;
use App\Repository\SERO\ContinuationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/continuation')]
class ContinuationController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_continuation_index', methods: ['GET'])]
    public function index(ContinuationRepository $continuationRepository): Response
    {
        return $this->render('sero/continuation/index.html.twig', [
            'continuations' => $continuationRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'new_contuoation', methods: ['GET', 'POST'])]
    public function new(Request $request, Application $application, EntityManagerInterface $entityManager): Response
    {
        $continuation = new Continuation();
        $contuoationForm = $this->createForm(ContinuationType::class, $continuation);
        $contuoationForm->handleRequest($request);

        if ($contuoationForm->isSubmitted() && $contuoationForm->isValid()) {
            $continuation->setApplication($application);
            $continuation->setRequestedBy($this->getUser());
            $continuation->setRequestedAt(new \DateTime());
            
            $entityManager->persist($continuation);
            $entityManager->flush();
            $this->addFlash("success", "The request sent successfully!");

            return $this->redirectToRoute('application_show', ["id" => $application->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/continuation/new.html.twig', [
            'continuation' => $continuation,
            'contuoationForm' => $contuoationForm,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_continuation_show', methods: ['GET'])]
    public function show(Continuation $continuation): Response
    {
        return $this->render('sero/continuation/show.html.twig', [
            'continuation' => $continuation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_continuation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Continuation $continuation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContinuationType::class, $continuation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_continuation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/continuation/edit.html.twig', [
            'continuation' => $continuation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_continuation_delete', methods: ['POST'])]
    public function delete(Request $request, Continuation $continuation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$continuation->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($continuation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_continuation_index', [], Response::HTTP_SEE_OTHER);
    }
}
