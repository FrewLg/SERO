<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Training;
use App\Form\CouponType;
use App\Repository\CouponRepository;
use App\Repository\DirectorateRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/coupon')]
class CouponController extends AbstractController
{
    #[Route('/{id}/s', name: 'tr_coupon', methods: ['GET'])]
    public function index(Training $training, UserRepository $userRepository,  PaginatorInterface $paginator,  CouponRepository $couponRepository, DirectorateRepository $directorateRepository, Request $request): Response
    {
 
        // couponRepository->findBy(['training'=>$training]);
        $res = $paginator->paginate(
            // Doctrine Query, not results
            $couponRepository->findBy(['training'=>$training]),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            25
        );
        return $this->render('coupon/index.html.twig', [
        'training' => $training,
        'allusers' => $userRepository->findAll(),

        'alldirectorates'=>  $directorateRepository->findAll(),
        'coupons' => $res,

        
        ]);
    }
    #[Route('/', name: 'app_coupon_index', methods: ['GET'])]
    public function indextwo( CouponRepository $couponRepository, PaginatorInterface $paginator, Request $request): Response
    {
 
        $res = $paginator->paginate(
            // Doctrine Query, not results
            $couponRepository->findAll(),
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            25
        );
        return $this->render('coupon/all.html.twig', [
            
        'coupons' => $res,

        
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
