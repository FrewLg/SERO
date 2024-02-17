<?php

namespace App\Controller;

use App\Entity\Fund;
use App\Entity\FundTransaction;
use App\Form\FundTransactionType;
use App\Form\FundType;
use App\Repository\FundTransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/fund-transaction')]
class FundTransactionController extends AbstractController
{
    #[Route('/{id}/transactions', name: 'app_fund_transactions', methods: ['GET'])]
    public function index(FundTransactionRepository $fundTransactionRepository, Fund $fund): Response
    {
        return $this->render('fund_transaction/index.html.twig', [
            'fund_transactions' => $fundTransactionRepository->findBy(['fundName'=>$fund->getId()]),
        ]);
    }

    #[Route('/all', name: 'app_fund_transaction_index', methods: ['GET'])]
    public function alltr(FundTransactionRepository $fundTransactionRepository): Response
    {
        return $this->render('fund_transaction/index.html.twig', [
            'fund_transactions' => $fundTransactionRepository->findAll(),
        ]);
    }

    #[Route('/{id}/ded', name: 'app_fund_transaction_new', methods: ['GET', 'POST'])]
    public function transact(Request $request, Fund $fundname, 
    FundTransactionRepository $fundTransactionRepository,
        EntityManagerInterface $entityManager): Response
    {
        $fundTransaction = new FundTransaction();
        $form = $this->createForm(FundTransactionType::class, $fundTransaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $alltransactions =  $fundTransactionRepository->findBy(['fundName'=>$fundname->getId()]);

            $availablefund=$fundname->getBudget()- $fundTransaction->getDeducted();
             
                $sum = 0;
                foreach ($alltransactions as $number) {
                    $sum += $number->getDeducted();
                }
                echo $sum;

            if($fundTransaction->getDeducted()>$fundname->getBudget()-$sum){

                $this->addFlash("danger", "Available fund is less than you have requested !");
                return $this->redirectToRoute('app_fund_transaction_new', ['id'=>$fundname->getId()], Response::HTTP_SEE_OTHER);
           
            }
            $fundTransaction->setReferenceNubmer("ETF". $fundTransaction->getId().rand(1,99999));
            $fundTransaction->setCurrentBalance( $availablefund);
            // $fundname->setAvailableBudget($availablefund);
            $fundTransaction->setDeductedAt(new \Datetime());
            $fundTransaction->setCreatedBy($this->getUser());
           
            $entityManager->persist($fundTransaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_fund_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fund_transaction/new.html.twig', [
            'fund_transaction' => $fundTransaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fund_transaction_show', methods: ['GET'])]
    public function show(FundTransaction $fundTransaction): Response
    {
        return $this->render('fund_transaction/show.html.twig', [
            'fund_transaction' => $fundTransaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fund_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FundTransaction $fundTransaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FundTransactionType::class, $fundTransaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fund_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fund_transaction/edit.html.twig', [
            'fund_transaction' => $fundTransaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fund_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, FundTransaction $fundTransaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fundTransaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($fundTransaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_fund_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
