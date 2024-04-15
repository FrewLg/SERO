<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewAssignment;
use App\Entity\SERO\ReviewChecklistGroup;
use App\Form\SERO\ReviewAssignmentType;
use App\Repository\SERO\ReviewAssignmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/sero/review-assignment')]
class ReviewAssignmentController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_review_assignment_index', methods: ['GET'])]
    public function index(ReviewAssignmentRepository $reviewAssignmentRepository): Response
    {
        return $this->render('sero/review_assignment/index.html.twig', [
            'review_assignments' => $reviewAssignmentRepository->findAll(),
        ]);
    }

    #[Route('/myassigned', name: 'irb_my_assigned', methods: ['GET','POST'])]
   
    public function myassigned(Request $request,ReviewAssignmentRepository $reviewAssignmentRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response {

      
        $this->denyAccessUnlessGranted('ROLE_USER');

        $me = $this->getUser();
        $this_is_me = $this->getUser();
       

        $myassigned = $reviewAssignmentRepository->findBy(['irbreviewer' => $this_is_me, 'closed' => NULL], ["id" => "DESC"]);
        $all = $entityManager->getRepository(ReviewAssignment::class)->findBy(['irbreviewer' => $this_is_me, 'closed' => 1], ["id" => "DESC"]);
        // }
        ////// if no throw exception
        $myassigneds = $paginator->paginate(
            // Doctrine Query, not results
            $myassigned,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
  
        #######################
       
        #################################################

        return $this->render('sero/review_assignment/myassigned.html.twig', [
            'closeds' => $all,
            'myreviews' => $myassigneds,
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_review_assignment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewAssignment = new ReviewAssignment();
        $form = $this->createForm(ReviewAssignmentType::class, $reviewAssignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewAssignment);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_review_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_assignment/new.html.twig', [
            'review_assignment' => $reviewAssignment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_review_assignment_show', methods: ['GET'])]
    public function show(ReviewAssignment $reviewAssignment): Response
    {
        return $this->render('sero/review_assignment/show.html.twig', [
            'review_assignment' => $reviewAssignment,
        ]);
    }


    #[Route('/{id}/revision', name: 'review_result', methods: ['GET'])]
    public function revisionresult(ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {
        $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();

        return $this->render('sero/review_checklist/tabs/responses.html.twig', [
            'review_assignment' => $reviewAssignment,
            'irb_review_checklist_group' => $irb_review_checklist_group,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_review_assignment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewAssignmentType::class, $reviewAssignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_review_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_assignment/edit.html.twig', [
            'review_assignment' => $reviewAssignment,
            'form' => $form,
        ]);
    }


    

    #[Route('/{id}', name: 'app_s_e_r_o_review_assignment_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewAssignment->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewAssignment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_review_assignment_index', [], Response::HTTP_SEE_OTHER);
    }
}
