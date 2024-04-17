<?php

namespace App\Controller\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\Amendment;
use App\Entity\SERO\ApplicationFeedback;
use App\Entity\SERO\ReviewChecklistGroup;
use App\Form\SERO\ReviewChecklistGroupType;
use App\Entity\SERO\ReviewAssignment;
use App\Entity\SERO\ReviewerResponse;
use App\Entity\SERO\ReviewChecklist;

use App\Form\SERO\ApplicationFeedbackType;
use App\Form\SERO\ApplicationType;
use App\Form\SERO\AmendmentType;
use App\Repository\ApplicationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use App\Repository\SERO\ApplicationFeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/protocol')]
class ApplicationController extends AbstractController
{
    #[Route('/', name: 'application_index', methods: ['GET'])]
    public function index(ApplicationRepository $applicationRepository): Response
    {
        return $this->render('sero/application/index.html.twig', [
            'applications' => $applicationRepository->findAll(),
        ]);
    }

    #[Route('/my-applications', name: 'myapplication', methods: ['GET', 'POST'])]
    public function myapplications(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginatorInterface): Response
    {
        $me = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $allappsbyme =  array_reverse($em->getRepository(Application::class)->findBy(array('submittedBy' => $me)));

        $data = $paginatorInterface->paginate(
            $allappsbyme,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('application/my_application.html.twig', [
            'applications' => $data,
        ]);
    }


    #[Route('/new', name: 'app_s_e_r_o_application_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($application);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/application/new.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/history', name: 'app_history', methods: ['GET'])]
    public function history(Application $application, Request $request, EntityManagerInterface $entityManager, ApplicationFeedbackRepository $appferepo, MailerInterface $mailer): Response
    {

        return $this->render('sero/application/application-history.html.twig', [
            'application' => $application,

        ]);

    }
  

    #[Route('/{id}/revise', name: 'make_a_review', methods: ['GET', 'POST'])]
    public function revise(Request $request, ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {

        if ($request->request->get('review-checklist') && $request->request->get('review-comments')) {
            $commentArray = $request->get('comment');
            $checks = $request->get('checklist');
            foreach ($checks as $key => $value) {
                $theChecklists[] =   $value;
                $theKeys[] =   $key;
                if ($value == null) {
                    continue;
                }
            }
            foreach ($commentArray as $key2 => $value2) {
                $comments[] =   $value2;
            }
            $length = count($checks);


            for ($i = 0; $i < $length; $i++) {

                $theComment = $comments[$i];

                $theEmail = $theChecklists[$i];
                $theKey = $theKeys[$i];
                $reviewerResponse = new ReviewerResponse();

                $reviewerResponse->setReviewAssignment($reviewAssignment);
                $reviewerResponse->setReviewedBy($this->getUser());
                $reviewerResponse->setAnswer($theEmail);
                $reviewerResponse->setChecklist($entityManager->getRepository(ReviewChecklist::class)->find($theKey));

                $reviewerResponse->setComment($theComment);
                $entityManager->persist($reviewerResponse);
            }


            $entityManager->flush();

            $this->addFlash("success", "Review results saved successfully!.");
            return $this->redirectToRoute('review_result', ['id' => $reviewAssignment->getId()]);
        }

        $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();

        return $this->render('sero/review_checklist/chcklists.html.twig', [
            'review_assignment' => $reviewAssignment,
            'irb_review_checklist_group' => $irb_review_checklist_group,
        ]);
    }


        #[Route('/{id}', name: 'app_s_e_r_o_application_show', methods: ['GET'])]
        public function show(Application $application, Request $request, EntityManagerInterface $entityManager, ApplicationFeedbackRepository $appferepo, MailerInterface $mailer): Response
        {
        if ($request->request->get('renewal')) {
            if (true) {
                $renewal = new RenewalRequest();
                $renewal->setApplication($application);
                $renewal->setRequestedAt(new DateTime());
                $entityManager->persist($renewal);
                $entityManager->flush();
                $this->addFlash("success", "Revision sent successfully");
            } else {
                $this->addFlash("danger", "You can't renew IRB clearance for this application");
            }
            return $this->redirectToRoute('application_show', ["id" => $application->getId()], Response::HTTP_SEE_OTHER);
        }
        $amendment = new Amendment();
        $amendment->setApplication($application);
        $form = $this->createForm(AmendmentType::class, $amendment);
        $form->handleRequest($request);

        // $revision = new Revision();
        // $revision->setApplication($application);
        // foreach ($entityManager->getRepository(AttachmentType::class)->findAll() as $key => $value) {

        //     $attachment =  new RevisionAttachment();
        //     $attachment->setType($value);
        //     $revision->addRevisionAttachments($attachment);
        // }
        // $form2 = $this->createForm(RevisionType::class, $revision);
        // $form2->handleRequest($request);
        // $review = $entityManager->getRepository(IRBReview::class)->findOneBy(['application' => $application]);

        if ($form->isSubmitted() && $form->isValid()) {
            $att = $request->files->get('amendment')["attachment"];
            foreach ($att as $key => $value) {
                $amendmentAtachment = new AmendmentAttachment();
                $amendmentAtachment->setUploadFile($value);
                $amendmentAtachment->setName($value->getClientOriginalName());
                $amendmentAtachment->setAmendment($amendment);
                $entityManager->persist($amendmentAtachment);
            }
            $entityManager->persist($amendment);
            $entityManager->flush();
            $this->addFlash("success", "Amendment requested successfully");
            return $this->redirectToRoute('application_show', ["id" => $application->getId()], Response::HTTP_SEE_OTHER);
        }

        // if ($form2->isSubmitted() && $form2->isValid()) {
        //     foreach ($revision->getRevisionAttachments() as $k => $val) {
        //         if (!$val->getChecked()) {
        //             $revision->removeRevisionAttachments($val);
        //         }
        //     }
        //     $entityManager->persist($revision);
        //     $entityManager->flush();
        //     $this->addFlash("success", "Revision sent successfully");
        //     return $this->redirectToRoute(
        //         'application_show',
        //         ["id" => $application->getId()],
        //         Response::HTTP_SEE_OTHER
        //     );
        // }

        #################Feedback
        $applicationFeedback = new ApplicationFeedback();
        $feedbackForm = $this->createForm(ApplicationFeedbackType::class, $applicationFeedback);
        $feedbackForm->handleRequest($request);

        // if ($feedbackForm->isSubmitted() && $feedbackForm->isValid()) {
        //     $applicationFeedback->setApplication($application);

             
        //     ######Attachment###
        //     if ($feedbackForm->get('attachement')->getData()) {
        //         $attachement = $feedbackForm->get('attachement')->getData();

        //         $file_name = 'Feedback' . md5(uniqid()) . '.' . $attachement->guessExtension();
        //         $attachement->move($this->getParameter('uploads_folder'), $file_name);
        //         $applicationFeedback->setAttachment($file_name);
        //     }
        //     #############SEnd email if checked#################

        //     if ($applicationFeedback->getSendMail() || $feedbackForm->get('sendMail')->getData() == 1) {

        //         $this->addFlash("success", "Feedback sent also sent via email successfully");
        //         $att = $feedbackForm->get('attachement')->getData();
        //         if ($att) {
        //             $withattachement = 'with attachement';
        //         } else {
        //             $withattachement = '';
        //         }
        //         $subject = "Response given to your Application";
        //         $body = "Your IRB application recently given a feedback" . $withattachement . " via our portal. Please take a look details of the feedback below.<br>" . $applicationFeedback->getDescription();
        //         $title = $applicationFeedback->getApplication()->getTitle();
        //         $theFirstName = $applicationFeedback->getApplication()->getSubmittedBy()->getUserInfo()->getFirstName();
        //         $app_url = "irb/application/" . $applicationFeedback->getApplication()->getId();
        //         $theEmail = $applicationFeedback->getApplication()->getSubmittedBy()->getEmail();
        //         $email = (new TemplatedEmail())
        //             ->from(new Address('sero@ephi.gov.et', $this->getParameter('app_name')))
        //             ->to(new Address($applicationFeedback->getApplication()->getSubmittedBy()->getEmail(), $applicationFeedback->getApplication()->getSubmittedBy()->getUserInfo()))
        //             // ->cc(new Address($alternative_email[$i], $theFirstNames[$i]))
        //             ->subject($subject)
        //             ->htmlTemplate('emails/irb_reviewer_response.html.twig')
        //             ->context([
        //                 'subject' => $subject,
        //                 'suffix' => $applicationFeedback->getApplication()->getSubmittedBy()->getUserInfo()->getSuffix(),
        //                 'body' => $body,
        //                 'title' => $title,
        //                 'submission_url' => $app_url,
        //                 'name' => $theFirstName,
        //                 'Authoremail' => $theEmail,
        //             ]);
        //         // dd($reviewAssignment->getApplication());
        //         $mailer->send($email);
        //     }

        //     #############SEnd email if checked#################
        //     ######Attachment###
        //     $applicationFeedback->setCreatedAt(new \DateTime());
        //     $applicationFeedback->setFeedbackFrom($this->getUser());
        //     $appferepo->add($applicationFeedback);
        //     return $this->redirectToRoute(
        //         'application_show',
        //         ["id" => $application->getId()],
        //         Response::HTTP_SEE_OTHER
        //     );
        // }

        #################Feedback


        $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();
        $reviewAssignment = $entityManager->getRepository(ReviewAssignment::class)->findBy(['application' => $application, 'closed' => 1]);

        // $reviews = $entityManager->getRepository(IRBReview::class)->findBy(['application' => $application, 'reviewed_by' => $this->getUser()]);

        return $this->render('sero/application/show.html.twig', [
            'appfeedbfrom' => $feedbackForm->createView(),
            'irb_review_checklist_group' => $irb_review_checklist_group,
            'review_assignment' => $reviewAssignment,
            // 'reviews' => $reviews,
            'application' => $application,
            'amendment' => $amendment,
            'form' => $form->createView(),
            // 'form2' => $form2->createView(),
            // 'review' => $review,
            // 'subject_category' => $entityManager->getRepository(ResearchSubjectCategory::class)->findAll(),
            // 'mitigation_strategy_group' => $entityManager->getRepository(MitigationStrategyGroup::class)->findAll(),
            // 'review_status_group' => $entityManager->getRepository(ReviewStatusGroup::class)->findAll()
        ]);
    }


    #[Route('/{id}/edit', name: 'app_s_e_r_o_application_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Application $application, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/application/edit.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_application_delete', methods: ['POST'])]
    public function delete(Request $request, Application $application, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $application->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($application);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_application_index', [], Response::HTTP_SEE_OTHER);
    }
}
