<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ReviewAssignment;
use App\Entity\SERO\Application;
use App\Entity\SERO\ReviewChecklistGroup;
use App\Form\SERO\ReviewAssignmentType;
use App\Form\SERO\SecondaryReviewerAssignmentType;
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
   

    #[Route('/{id}/assign', name: 'assign_reviewer', methods: ['GET', 'POST'])]

    public function assignreviewer(Request $request, EntityManagerInterface $entityManager,  Application $application,   MailerInterface $mailer, ReviewAssignmentRepository $reviewAssignmentRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_VICE_CHAIR');


        // if ($application->getSubmittedBy() == $this->getUser()) {
        //     $this->addFlash('danger', 'Sorry! You can not assign by yourself a reviewer to the application you made!');
        //     return $this->redirectToRoute('application_index');
        // }
        ///// check if the application is completed or not
        $reviewAssignment = new ReviewAssignment();
        $reviewAssignment->setStatus(1);
        $reviewAssignment->setApplication($application);


        // $messages = $entityManager->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'REVIEW_INVITATION']);
        // $subject = $messages->getSubject();
        // $body = $messages->getBody();
        // $title = $application->getTitle();

        $form = $this->createForm(ReviewAssignmentType::class, $reviewAssignment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$reviewAssignment->getIrbreviewer()) {
                return $this->redirectToRoute('assign_reviewer', array('id' => $application->getId()));
            }

            $reviewAssignment->setApplication($application);
            $reviewAssignment->setInvitationSentAt(new \DateTime());
            $reviewAssignment->setReviewerType(1);

            // dd($application->getId());
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

            return $this->redirectToRoute('assign_reviewer', array('id' => $application->getId()));
        }

        $extssignment = new ReviewAssignment();
        $extssignment->setStatus(1);
        $extssignment->setApplication($application);
        $extform = $this->createForm(SecondaryReviewerAssignmentType::class, $extssignment);
        $extform->handleRequest($request);
        if ($extform->isSubmitted() && $extform->isValid()) {

            $extssignment->setApplication($application);
            $extssignment->setInvitationSentAt(new \DateTime());
            $extssignment->setReviewerType(2);
            $entityManager->persist($extssignment);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Secondary Protocol reviewer assigned successfully!'
            );
            return $this->redirectToRoute('assign_reviewer', array('id' => $application->getId()));
        }

        $reviewAssignments = $entityManager->getRepository('App\Entity\SERO\ReviewAssignment')->findBy(['application' => $application]);

        ////////////////External reviewer
        return $this->render('sero/review_assignment/new.html.twig', [
            'irb_review_assignment' => $reviewAssignments,
            'review_assignments' => $reviewAssignmentRepository->findBy(['irbreviewer' => $this->getUser()]),
            'application' => $application,
            'extform' => $extform,
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
 

    #[Route('/{id}/revision', name: 'review_result', methods: ['GET'])]
    public function revisionresult(ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {
        $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();

        return $this->render('sero/review_checklist/tabs/responses.html.twig', [
            'review_assignment' => $reviewAssignment,
            'irb_review_checklist_group' => $irb_review_checklist_group,

        ]);
    }
 


    #[Route('/{id}', name: 'review_assignment_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reviewAssignment->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($reviewAssignment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('assign_reviewer', array('id' => $reviewAssignment->getApplication()->getId()));

        // return $this->redirectToRoute('review_assignment_index', [], Response::HTTP_SEE_OTHER);
    }
}
