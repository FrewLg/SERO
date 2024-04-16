<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewChecklist;
use App\Entity\SERO\ReviewAssignment;
use App\Entity\SERO\ReviewChecklistGroup;
use App\Entity\SERO\ReviewerResponse;
use App\Form\SERO\ReviewChecklistType;
use App\Repository\ReviewChecklistRepository;  
use App\Repository\SERO\ReviewChecklistGroupRepository;  
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/checklists')]
class ReviewChecklistController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_review_checklist_index', methods: ['GET'])]
    public function index(ReviewChecklistRepository $reviewChecklistRepository, ReviewChecklistGroupRepository $reviewChecklistGroupRepository): Response
    {
        return $this->render('sero/review_checklist/index.html.twig', [
            'review_checklists' => $reviewChecklistRepository->findAll(),
            'checklist_group' => $reviewChecklistGroupRepository->findAll(),

        ]);
    }

    #[Route('/{id}/revise', name: 'review_application', methods: ['GET', 'POST'])]
    
    public function revise(Request $request, ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager ): Response {
        
         if ($request->request->get('review-checklist') ) {
            
            
            foreach ($request->get('checklist') as $key => $value  ) {

                   
                if (!$value) {
                    continue;
                }
                $reviewerResponse = new ReviewerResponse();

                $reviewerResponse->setReviewAssignment($reviewAssignment);
                     
                $reviewerResponse->setAnswer($value);
                $reviewerResponse->setChecklist($entityManager->getRepository(ReviewChecklist::class)->find($key));
                $commentArray= $request->get('comment') ;

                // $reviewerResponse->setComment($request->get('comment')[$key] );

                 #####
                 foreach ($commentArray as $newkey => $newvalue  ) {
                        $reviewerResponse->setComment($newvalue );

                 }

                //     if($entityManager->getRepository(ReviewerResponse::class)->findBy(['checklist'=>$key , 'reviewAssignment'=>$reviewAssignment->getId()])
                //     //  && $entityManager->getRepository(ReviewerResponse::class)->findBy(['checklist'=>$key , 'reviewAssignment'=>$reviewAssignment->getId(), 'comment'=>NULL ])

                //     ){
                //         continue;
                //     }
                //     else{
                //         $commentval=$newvalue;
                //         $reviewerResponse->setComment($commentval );
                //     }
                // }
                 

                $entityManager->persist($reviewerResponse);

             
            // $entityManager->flush();

              }
             #####
            $entityManager->flush();

            $this->addFlash("success", "Review sent.");
            return $this->redirectToRoute('review_result',['id'=>$reviewAssignment->getId()]);
        }

         $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();

        return $this->render('sero/review_checklist/chcklists.html.twig', [
            'review_assignment' => $reviewAssignment,
            'irb_review_checklist_group' => $irb_review_checklist_group,
         ]);
    }
    #[Route('/new', name: 'app_s_e_r_o_review_checklist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewChecklist = new ReviewChecklist();
        $form = $this->createForm(ReviewChecklistType::class, $reviewChecklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewChecklist);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_review_checklist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_checklist/new.html.twig', [
            'review_checklist' => $reviewChecklist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_review_checklist_show', methods: ['GET'])]
    public function show(ReviewChecklist $reviewChecklist): Response
    {
        return $this->render('sero/review_checklist/show.html.twig', [
            'review_checklist' => $reviewChecklist,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_review_checklist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewChecklist $reviewChecklist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewChecklistType::class, $reviewChecklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_review_checklist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_checklist/edit.html.twig', [
            'review_checklist' => $reviewChecklist,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_review_checklist_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewChecklist $reviewChecklist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewChecklist->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewChecklist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_review_checklist_index', [], Response::HTTP_SEE_OTHER);
    }
}
