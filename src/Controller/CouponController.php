<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Training;
use App\Form\CouponType;
use App\Repository\CouponRepository;
use App\Repository\DirectorateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/coupon')]
class CouponController extends AbstractController
{
    #[Route('/{id}/s', name: 'tr_coupon', methods: ['GET'])]
    public function index(Training $training, CouponRepository $couponRepository, DirectorateRepository $directorateRepository): Response
    {
 
        return $this->render('coupon/index.html.twig', [
            'training' => $training,
        'alldirectorates'=>  $directorateRepository->findAll(),
        'coupons' => $couponRepository->findBy(['training'=>$training]),

        
        ]);
    }
    #[Route('/', name: 'app_coupon_index', methods: ['GET'])]
    public function indextwo(Training $training, CouponRepository $couponRepository, DirectorateRepository $directorateRepository): Response
    {
 
        return $this->render('coupon/index.html.twig', [
            'training' => $training,
        'alldirectorates'=>  $directorateRepository->findAll(),
        'coupons' => $couponRepository->findBy(['training'=>$training]),

        
        ]);
    }
    #[Route('/new', name: 'app_coupon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coupon = new Coupon();
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coupon);


            $coupon->setCreatedAt(new \Datetime());

            $entityManager->flush();

            return $this->redirectToRoute('app_coupon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon/new.html.twig', [
            'coupon' => $coupon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_show', methods: ['GET'])]
    public function show(Coupon $coupon): Response
    {
        return $this->render('coupon/show.html.twig', [
            'coupon' => $coupon,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coupon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coupon $coupon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CouponType::class, $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coupon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coupon/edit.html.twig', [
            'coupon' => $coupon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coupon_delete', methods: ['POST'])]
    public function delete(Request $request, Coupon $coupon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coupon->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coupon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coupon_index', [], Response::HTTP_SEE_OTHER);
    }
}
