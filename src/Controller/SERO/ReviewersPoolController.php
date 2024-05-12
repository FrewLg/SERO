<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewersPool;
use App\Entity\User;
use App\Form\SERO\ReviewersPoolType;
use App\Repository\SERO\ReviewersPoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use LDAP\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('{_locale<%app.supported_locales%>}/reviewers-pool')]
class ReviewersPoolController extends AbstractController
{
    #[Route('/', name: 'reviewers_pool_index', methods: ['GET'])]
    public function index(ReviewersPoolRepository $reviewersPoolRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_VICE_CHAIR');

        $file = $request->files->get('file'); // get the file from the sent request
   
        $fileFolder = __DIR__ . '/../../public/uploads/';  //choose the folder in which the uploaded file will be stored
       
        $filePathName = md5(uniqid()) . $file->getClientOriginalName();
           // apply md5 function to generate an unique identifier for the file and concat it with the file extension  
                 try {
                     $file->move($fileFolder, $filePathName);
                 } catch (FileException $e) {
                     dd($e);
                 }
         $spreadsheet = IOFactory::load($fileFolder . $filePathName); // Here we are able to read from the excel file 
         $row = $spreadsheet->getActiveSheet()->removeRow(1); // I added this to be able to remove the first file line 
         $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true); // here, the read data is turned into an array
      dd($sheetData);
      $entityManager = $this->getDoctrine()->getManager(); 
      foreach ($sheetData as $Row) 
          { 
  
              $first_name = $Row['A']; // store the first_name on each iteration 
              $last_name = $Row['B']; // store the last_name on each iteration
              $email= $Row['C'];     // store the email on each iteration
              $phone = $Row['D'];   // store the phone on each iteration
  
              $user_existant = $entityManager->getRepository(ReviewersPool::class)->findOneBy(array('email' => $email)); 
                  // make sure that the user does not already exists in your db 
            //   if (!$user_existant) 
            //    {   
            //       $student = new ReviewersPool(); 
            //       $student->setFirstName($first_name);           
            //       $student->setLastName($last_name);
            //       $student->setEmail($email);
            //       $student->setPhone($phone);
            //       $entityManager->persist($student); 
            //       $entityManager->flush(); 
            //        // here Doctrine checks all the fields of all fetched data and make a transaction to the database.
            //    } 
              if (!$user_existant) 
            //    for ($i = 0; $i < $totalPossiblecoupons; $i++) {
                $coupon = new User();

                $coupon->setEmail("f" . $i . "irew" . $value->getAcronym() . ".legese74" . $i . "@gmail.com");
                $coupon->setDirectorate(2);
                $coupon->setRoles(["ROLE_ADMIN"]);
                $coupon->setPassword($i . "frew.legese@gmail.com");
                $entityManager->persist($coupon);
                $entityManager->flush();
           
        // }

        //   } 
    //   return $this->json('users registered', 200); 
        }
        return $this->render('sero/reviewers_pool/index.html.twig', [
            'reviewers_pools' => $reviewersPoolRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'reviewers_pool_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewersPool = new ReviewersPool();
        $form = $this->createForm(ReviewersPoolType::class, $reviewersPool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reviewersPool->setDateRegistered(new \DateTime());
            $entityManager->persist($reviewersPool);
            $entityManager->flush();

            return $this->redirectToRoute('reviewers_pool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/reviewers_pool/new.html.twig', [
            'reviewers_pool' => $reviewersPool,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reviewers_pool_show', methods: ['GET'])]
    public function show(ReviewersPool $reviewersPool): Response
    {
        return $this->render('sero/reviewers_pool/show.html.twig', [
            'reviewers_pool' => $reviewersPool,
        ]);
    }

    #[Route('/{id}/edit', name: 'reviewers_pool_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewersPool $reviewersPool, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewersPoolType::class, $reviewersPool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reviewers_pool_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/reviewers_pool/edit.html.twig', [
            'reviewers_pool' => $reviewersPool,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'reviewers_pool_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewersPool $reviewersPool, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewersPool->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewersPool);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reviewers_pool_index', [], Response::HTTP_SEE_OTHER);
    }
}
