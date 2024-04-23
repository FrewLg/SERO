<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewAssignment;
use App\Entity\SERO\Application;
use App\Entity\SERO\ReviewChecklistGroup;
use App\Form\SERO\ReviewAssignmentType;
use App\Repository\SERO\ReviewAssignmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('{_locale<%app.supported_locales%>}/assignment')]
class ReviewAssignmentController extends AbstractController
{
    #[Route('/', name: 'review_assignment_index', methods: ['GET'])]
    public function index(ReviewAssignmentRepository $reviewAssignmentRepository): Response
    {
        return $this->render('sero/review_assignment/index.html.twig', [
            'review_assignments' => $reviewAssignmentRepository->findAll(),
        ]);
    }


    #[Route('/{id}/assign', name: 'assign_reviewer', methods: ['GET', 'POST'])]

    public function assignreviewer(Request $request, EntityManagerInterface $entityManager,  Application $submission,   MailerInterface $mailer, ReviewAssignmentRepository $reviewAssignmentRepository): Response
    {

        // $this->denyAccessUnlessGranted('assn_clg_cntr');


        // if ($submission->getSubmittedBy() == $this->getUser()) {
        //     $this->addFlash('danger', 'Sorry! You can not assign by yourself a reviewer to the submission you made!');
        //     return $this->redirectToRoute('application_index');
        // }
        ///// check if the submission is completed or not
        $reviewAssignment = new ReviewAssignment();
        $reviewAssignment->setStatus(1);
        $reviewAssignment->setApplication($submission);


        // $messages = $entityManager->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'REVIEW_INVITATION']);
        // $subject = $messages->getSubject();
        // $body = $messages->getBody();
        // $title = $submission->getTitle();

        $form = $this->createForm(ReviewAssignmentType::class, $reviewAssignment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$reviewAssignment->getIrbreviewer()) {
                return $this->redirectToRoute('assign_reviewer', array('id' => $submission->getId()));
            }

            $reviewAssignment->setApplication($submission);
            $duedate = $reviewAssignment->getDuedate();
            $reviewAssignment->setInvitationSentAt(new \DateTime());
            // dd($submission->getId());
            // $reviewAssignment->getApplication()->setStatus($entityManager->getRepository(IRBStatus::class)->find(2));

            $entityManager->persist($reviewAssignment);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Research reviewer assigned successfully!'
            );
            // $suffix = $reviewAssignment->getIRBReviewer();
            // $theFirstName = $reviewAssignment->getIRBReviewer()->getUserInfo()->getFirstName();
            // $invitation_url = "irb/reviewer-assignment/" . $reviewAssignment->getId() . "/revise/";
            // $theEmail = $reviewAssignment->getIRBReviewer()->getEmail();

            // dd( $form);
            // $email = (new TemplatedEmail())
            //     ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
            //     ->to(new Address($reviewAssignment->getIRBReviewer()->getEmail(), $reviewAssignment->getIRBReviewer()->getUserInfo()))
            //     // ->cc(new Address($alternative_email[$i], $theFirstNames[$i]))
            //     ->subject($subject)
            //     ->htmlTemplate('emails/reviewerinvitation.html.twig')
            //     ->context([
            //         'subject' => $subject,
            //         'suffix' => $suffix,
            //         'body' => $body,
            //         'title' => $title,
            //         'college' => $reviewAssignment->getIRBReviewer()->getUserInfo()->getCollege(),
            //         'reviewerinvitation_URL' => $invitation_url,
            //         'name' => $theFirstName,
            //         'Authoremail' => $theEmail,
            //     ]);
            // $mailer->send($email);

            return $this->redirectToRoute('assign_reviewer', array('id' => $submission->getId()));
        }

        $external_reviewAssignment = new ReviewAssignment();
        $external_reviewAssignment->setStatus(1);
        $external_reviewAssignment->setApplication($submission);

        // $external_reviewer_form = $this->createForm(ExternalIRBReviewAssignmentType::class, $external_reviewAssignment)->handleRequest($request);

        // if ($external_reviewer_form->isSubmitted() && $external_reviewer_form->isValid()) {

        //     // dd($external_reviewer_form->getData());
        //     $token = bin2hex(random_bytes(20));
        //     $external_reviewAssignment->setToken($token);
        //     $external_reviewAssignment->setInvitationDueDate(new \DateTime('+5 day'));
        //     $entityManager->persist($external_reviewAssignment);

        //     $entityManager->flush();

        //     //sent email
        //     // $mailHelper->sendEmail($external_reviewAssignment->getExternalirbrevieweremail(), "review assignment", "emails/reviewerinvitation.html.twig", [
        //     //     'subject' => $subject,
        //     //     'suffix' => $external_reviewAssignment->getExternalirbreviewerName(),
        //     //     'body' => $body,
        //     //     'title' => $title,
        //     //     'college' => " ",
        //     //     'reviewerinvitation_URL' => "external-irb-review/" . $token,
        //     //     'name' => $external_reviewAssignment->getExternalirbreviewerName(),
        //     //     'Authoremail' => $external_reviewAssignment->getExternalirbrevieweremail(),
        //     // ]);
        //     $this->addFlash("success", "External assigned successfully!!");
        //     return $this->redirectToRoute('assign_reviewer', array('id' => $submission->getId()));
        // }

        $reviewAssignments = $entityManager->getRepository('App\Entity\SERO\ReviewAssignment')->findBy(['application' => $submission]);

        ////////////////External reviewer
        return $this->render('sero/review_assignment/new.html.twig', [
            'irb_review_assignment' => $reviewAssignments,
            'review_assignments' => $reviewAssignmentRepository->findBy(['irbreviewer'=>$this->getUser()]),

            // 'external_reviewer_form' => $external_reviewer_form->createView(),
            'form' => $form->createView(),

        ]);
    }

    #[Route('/myassigned', name: 'my_assignment', methods: ['GET', 'POST'])]

    public function myassigned(Request $request, ReviewAssignmentRepository $reviewAssignmentRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
///Use ROLE_CHAIR instead

        $this->denyAccessUnlessGranted('ROLE_USER');
        $this_is_me = $this->getUser();
        $myassigned = $reviewAssignmentRepository->findBy(['irbreviewer' => $this_is_me, 'closed' => null], ["id" => "DESC"]);
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

    #[Route('/new', name: 'review_assignment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewAssignment = new ReviewAssignment();
        $form = $this->createForm(ReviewAssignmentType::class, $reviewAssignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewAssignment);
            $entityManager->flush();

            return $this->redirectToRoute('review_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_assignment/new.html.twig', [
            'review_assignment' => $reviewAssignment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'review_assignment_show', methods: ['GET'])]
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

    #[Route('/{id}/edit', name: 'review_assignment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewAssignmentType::class, $reviewAssignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('review_assignment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/review_assignment/edit.html.twig', [
            'review_assignment' => $reviewAssignment,
            'form' => $form,
        ]);
    }




    #[Route('/{id}', name: 'review_assignment_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reviewAssignment->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewAssignment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('review_assignment_index', [], Response::HTTP_SEE_OTHER);
    }
}
