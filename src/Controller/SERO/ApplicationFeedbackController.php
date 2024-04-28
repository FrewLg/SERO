<?php

namespace App\Controller\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\ApplicationFeedback;
use App\Form\SERO\ApplicationFeedbackType;
use App\Repository\SERO\ApplicationFeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
// use PhpOffice\PhpSpreadsheet\Calculation\LookupRef\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/application/feedback')]
class ApplicationFeedbackController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_application_feedback_index', methods: ['GET'])]
    public function index(ApplicationFeedbackRepository $applicationFeedbackRepository): Response
    {
        return $this->render('sero/application_feedback/index.html.twig', [
            'application_feedbacks' => $applicationFeedbackRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'feedback_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,
     Application $application, MailerInterface $mailer): Response
    {
        $applicationFeedback = new ApplicationFeedback();
        $feedbackForm = $this->createForm(ApplicationFeedbackType::class, $applicationFeedback);
        $feedbackForm->handleRequest($request);

        if ($feedbackForm->isSubmitted() && $feedbackForm->isValid()) {
            $applicationFeedback->setApplication($application);
            ######Attachment###
            if ($feedbackForm->get('attachement')->getData()) {
                $attachement = $feedbackForm->get('attachement')->getData();

                $file_name = 'Feedback' . md5(uniqid()) . '.' . $attachement->guessExtension();
                $attachement->move($this->getParameter('uploads_folder'), $file_name);
                $applicationFeedback->setAttachment($file_name);
            }
            #############SEnd email if checked#################

            if ($applicationFeedback->isSendMail() || $feedbackForm->get('sendMail')->getData() == 1) {

                $this->addFlash("success", "Feedback sent also sent via email successfully");
                $att = $feedbackForm->get('attachement')->getData();
                if ($att) {
                    $withattachement = 'with attachement';
                } else {
                    $withattachement = '';
                }
                $subject = "Response given to your Application";
                $body = "Your IRB application recently given a feedback" .
                 $withattachement . " via our portal. Please take a look details of the feedback below.<br>" .
                  $applicationFeedback->getDescription();
                $title = $applicationFeedback->getApplication()->getTitle();
                $theFirstName = $applicationFeedback->getApplication()->getSubmittedBy()->getProfile()->getFirstName();
                $app_url = "sero/protocol/" . $applicationFeedback->getApplication()->getId();
                $theEmail = $applicationFeedback->getApplication()->getSubmittedBy()->getEmail();
                $email = (new TemplatedEmail())
                    ->from(new Address('sero@ephi.gov.et', $this->getParameter('app_name')))
                    ->to(new Address($applicationFeedback->getApplication()->getSubmittedBy()->getEmail(),
                     $applicationFeedback->getApplication()->getSubmittedBy()->getProfile()))
                    // ->cc(new Address($alternative_email[$i], $theFirstNames[$i]))
                    ->subject($subject)
                    ->htmlTemplate('emails/irb_reviewer_response.html.twig')
                    ->context([
                        'subject' => $subject,
                        'suffix' => $applicationFeedback->getApplication()->getSubmittedBy()->getProfile(),
                        'body' => $body,
                        'title' => $title,
                        'submission_url' => $app_url,
                        'name' => $theFirstName,
                        'Authoremail' => $theEmail,
                    ]);
                // dd($reviewAssignment->getApplication());
                $mailer->send($email);
            }

            #############SEnd email if checked#################
            ######Attachment###
            $applicationFeedback->setCreatedAt(new \DateTime());
            $applicationFeedback->setFeedbackFrom($this->getUser());
            // $appferepo->add($applicationFeedback);
            return $this->redirectToRoute(
                'application_show',
                ["id" => $application->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        #################Feedback

        return $this->render('sero/application_feedback/new.html.twig', [
            'application_feedback' => $applicationFeedback,
            'form' => $feedbackForm,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_application_feedback_show', methods: ['GET'])]
    public function show(ApplicationFeedback $applicationFeedback): Response
    {
        return $this->render('sero/application_feedback/show.html.twig', [
            'application_feedback' => $applicationFeedback,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_application_feedback_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ApplicationFeedback $applicationFeedback, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ApplicationFeedbackType::class, $applicationFeedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_application_feedback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/application_feedback/edit.html.twig', [
            'application_feedback' => $applicationFeedback,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_application_feedback_delete', methods: ['POST'])]
    public function delete(Request $request, ApplicationFeedback $applicationFeedback, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $applicationFeedback->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($applicationFeedback);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_application_feedback_index', [], Response::HTTP_SEE_OTHER);
    }
}
