<?php

namespace App\Controller;

use App\Entity\Fund;
use App\Form\FundType;
use App\Repository\FundRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fund')]
class FundController extends AbstractController
{
    #[Route('/', name: 'app_fund_index', methods: ['GET'])]
    public function index(FundRepository $fundRepository): Response
    {
        return $this->render('fund/index.html.twig', [
            'funds' => $fundRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_fund_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fund = new Fund();
        $form = $this->createForm(FundType::class, $fund);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fund->setCreatedAt(new \Datetime());
            $fund->setCreatedBy($this->getUser());
 
           
            $entityManager->persist($fund);
            $entityManager->flush();

            return $this->redirectToRoute('app_fund_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fund/new.html.twig', [
            'fund' => $fund,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fund_show', methods: ['GET'])]
    public function show(Fund $fund): Response
    {
        return $this->render('fund/show.html.twig', [
            'fund' => $fund,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fund_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fund $fund, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FundType::class, $fund);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fund_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fund/edit.html.twig', [
            'fund' => $fund,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fund_delete', methods: ['POST'])]
    public function delete(Request $request, Fund $fund, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fund->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fund);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fund_index', [], Response::HTTP_SEE_OTHER);
    }
}
