<?php

namespace App\Controller\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\Amendment;
use App\Entity\SERO\ApplicationFeedback;
use App\Entity\SERO\Continuation;
use App\Entity\SERO\DecisionType;
use App\Entity\SERO\Icf;
use App\Entity\SERO\ReviewChecklistGroup;
use App\Form\SERO\ReviewChecklistGroupType;
use App\Entity\SERO\ReviewAssignment;
use App\Entity\SERO\ReviewerResponse;
use App\Entity\SERO\ReviewChecklist;
use App\Entity\SERO\Version;
use App\Form\SERO\ApplicationFeedbackType;
use App\Form\SERO\ApplicationType;
use App\Form\SERO\AmendmentType;
use App\Form\SERO\ContinuationType;
use App\Form\SERO\IcfType;
use App\Form\SERO\VersionType;
use App\Repository\ApplicationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use App\Repository\SERO\ApplicationFeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Helper\SEROHelper;
use Doctrine\DBAL\Types\DecimalType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;

#[Route('{_locale<%app.supported_locales%>}/protocol')]

class ApplicationController extends AbstractController
{
    #[Route('/', name: 'application_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em,   PaginatorInterface $paginatorInterface): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $allApps =  array_reverse($em->getRepository(Application::class)->findAll());
        $app = new Application();
        $form = $this->createForm(ApplicationType::class, $app);
        $form->handleRequest($request);
        if ($request->isXmlHttpRequest()) {
            $result = $em->getRepository(Application::class)
                ->findBy(
                    $request->get('live_search')
                );
            return new JsonResponse($result);
        }

        // $allappsbyme =  array_reverse($em->getRepository(Application::class)->findBy(array('submittedBy' => $me)));
        $result = $paginatorInterface->paginate(
            $allApps,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('sero/application/index.html.twig', [
            'applications' => $result,
            'form' => $form,
        ]);
    }

    #[Route('/my-applications', name: 'myapplication', methods: ['GET', 'POST'])]
    public function myapplications(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginatorInterface
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $me = $this->getUser();
        $allappsbyme =  array_reverse($em->getRepository(Application::class)->findBy(array('submittedBy' => $me)));
        $data = $paginatorInterface->paginate(
            $allappsbyme,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('sero/application/my_application.html.twig', [
            'applications' => $data,
        ]);
    }

    #[Route('/new', name: 'protocol_application_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        SEROHelper $seroHelper
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            ///
            //Add version//
            // dd($application);
            $protocolversion = $entityManager->getRepository(Version::class)->findBy(['application' => $application]);
            $protocolversions = count($protocolversion) + 1;
            $version = new Version();
            $version->setDate(new \DateTime());
            $version->setCreatedAt(new \DateTime());
            $application->setCreatedAt(new \DateTime());
            $version->setVersionNumber($protocolversions);
            $version->setApplication($application);
            // end add version
            $application->setSubmittedBy($this->getUser());
            $application->setIbcode($seroHelper->versionate($application));
            $application->setStatus(1);
            $entityManager->persist($application);
            // $reviewfile = $request->get('attachment');
            // dd($form->get('attachment'));
            if ($form->get('attachment')->getData()) {
                $versionAttachement = $form->get('attachment')->getData();
                $versionFileName = $seroHelper->fileNamer($version) . $versionAttachement->guessExtension();
                $versionAttachement->move($this->getParameter('uploads_folder'), $versionFileName);
                $version->setAttachment($versionFileName);
                // dd($form->get('attachmentType')->getData());
                $version->setAttachmentType($form->get('attachmentType')->getData());
            }
            //
            $user = $this->getUser();

            $subject = "A new protocol has been submitted";
            $pi_name = $user->getProfile();
            $invitation_url = 'en/protocol/' . $application->getId();

            $email = (new TemplatedEmail())
                ->from(new Address($this->getParameter('app_email'), $this->getParameter('app_name')))
                ->to($application->getSubmittedBy()->getEmail())
                ->bcc('frew.legese@gmail.com')
                ->replyTo('frew.legese@gmail.com')
                ->subject('Your application has been received!')
                ->htmlTemplate('emails/co-authorship-alert.html.twig')
                ->context([
                    'subject' => "Your application has been received",
                    "body" => "We have received your protocol application successfully. You have obtained a protol number:" . $application->getIbcode() .
                        ". Hence you can track your application status using a <a href='http://127.0.0.1:8008/en/protocol/" . $application->getId() . "/details'>link</a>",
                    'title' => $subject . " with this link " . $application->getId(),
                    'pi' => $pi_name,
                    'submission_url' => $invitation_url,
                    'name' => $application->getSubmittedBy(),
                    'Authoremail' => $application->getSubmittedBy()->getEmail(),
                ]);
            try {
                $mailer->send($email);
                $this->addFlash("success", "Application has been submitted successfully!.");
            } catch (TransportExceptionInterface $e) {
                $this->addFlash("danger", "Error while sending email!");
            }

            // endsend email
            $entityManager->persist($version);
            $entityManager->flush();
            return $this->redirectToRoute('application_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('sero/application/nativeWizard.html.twig', [
            // return $this->render('sero/application/new.html.twig', [
            'application' => $application,
            'protocolForm' => $form,
        ]);
    }

    #[Route('/{id}/history', name: 'app_history', methods: ['GET'])]
    public function history(Application $application, Request $request, EntityManagerInterface $entityManager, ApplicationFeedbackRepository $appferepo, MailerInterface $mailer): Response
    {

        return $this->render('sero/application/application-history.html.twig', [
            'application' => $application,

        ]);
    }

    #[Route('/email', name: 'email_send', methods: ['GET', 'POST'])]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from("SERO -EPHI <sero@ephi.gov.et>")
            ->to('frew.legese@gmail.com')
            ->bcc('firewlegese74@gmail.com')
            ->replyTo('frew.legese@gmail.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject('SERO@ephi.gov.et  Time for Test Symfony Mailer!')
            ->text('Sending a ser0@ephi.gov.etss Test emails 465 here again!')
            ->html('<br> It worked under the following Conf. Thanks! <br>
            <p>  Configuration line is here: MAILER_URL=smtp://ntcms@ephi.gov.et:n7cf455p4ssw0r6@mail.ephi.gov.et:587?encryption=tls&auth_mode=oauth !</p>');
        try {
            $mailer->send($email);
            // dd();TransportExceptionInterface
            $this->addFlash("success", "Email sent successfully!");
        } catch (TransportExceptionInterface $e) {

            $this->addFlash("danger", $e); //."Unable to send email!");
        }
        return $this->redirectToRoute('application_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/revise', name: 'make_a_review', methods: ['GET', 'POST'])]
    public function makerevise(Request $request, ReviewAssignment $reviewAssignment, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($reviewAssignment->getIrbreviewer()->getId() == $this->getUser()->getId() && $reviewAssignment->getReviewedAt() !== NULL) {
            $this->addFlash("warning", "Review response hase been already sent!.");

            return $this->redirectToRoute('review_result', ['id' => $reviewAssignment->getId()], Response::HTTP_SEE_OTHER);
        }
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

            $reviewAssignment->setReviewedAt(new \DateTime());
            $reviewAssignment->setClosed(1);
            $reviewAssignment->setStatus(1);

            $entityManager->persist($reviewAssignment);

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

    #[Route('/{id}/details', name: 'application_show', methods: ['GET', 'POST'])]
    public function show(Application $application, Request $request, EntityManagerInterface $entityManager, SEROHelper $seroHelper): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($request->request->get('renewal')) {
            if (true) {
                $renewal = new Continuation();
                $renewal->setApplication($application);
                $renewal->setRequestedAt(new \DateTime());
                $entityManager->persist($renewal);
                $entityManager->flush();
                $this->addFlash("success", "Revision sent successfully");
            } else {
                $this->addFlash("danger", "You can't renew IRB clearance for this application");
            }
            return $this->redirectToRoute(
                'application_show',
                ["id" => $application->getId()],
                Response::HTTP_SEE_OTHER
            );
        }
        $version = new Version();
        $versionForm = $this->createForm(VersionType::class, $version);
        $versionForm->handleRequest($request);
        $existingVersion = $entityManager->getRepository(Version::class)->findBy(['application' => $application]);
        if ($versionForm->isSubmitted() && $versionForm->isValid()) {
            $newVersion = count($existingVersion) + 1;
            $version->setDate(new \DateTime());
            $version->setCreatedAt(new \DateTime());
            $version->setVersionNumber($newVersion);
            $version->setApplication($application);
            if ($versionForm->get('attachment')->getData()) {
                $ver = $versionForm->getData();
                $versionAttachement = $versionForm->get('attachment')->getData();
                $versionFileName = $seroHelper->fileNamer($ver) . $versionAttachement->guessExtension();
                $versionAttachement->move($this->getParameter('uploads_folder'), $versionFileName);
                $version->setAttachment($versionFileName);
            }
            $entityManager->persist($version);
            $entityManager->flush();
            return $this->redirectToRoute(
                'application_show',
                ["id" => $application->getId()],
                Response::HTTP_SEE_OTHER
            );
        }
        $applicationFeedback = new ApplicationFeedback();
        $feedbackForm = $this->createForm(ApplicationFeedbackType::class, $applicationFeedback);
        $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();
        $decisions = $entityManager->getRepository(DecisionType::class)->findAll();
        ///
        $ammendment = new Amendment;
        $ammendmentForm = $this->createForm(AmendmentType::class, $ammendment);
        ///
        ///
        $contuoation = new Continuation;
        $contuoationForm = $this->createForm(ContinuationType::class, $contuoation);
        ///
        ///
        $icf = new Icf;
        $icfForm = $this->createForm(IcfType::class, $icf);
        $icfForm->handleRequest($request);

        if ($icfForm->isSubmitted() && $icfForm->isValid()) {
            $icf->setApplication($application);
            $icf->setCreatedAt(new \DateTime());
            $icf->setVersionNumber($seroHelper->icfVersion($application));

            if ($icfForm->get('attachment')->getData()) {
                $ver = $icfForm->getData();
                $icfAttachement = $icfForm->get('attachment')->getData();
                $icfFileName = $seroHelper->icfFileNamer($ver) . $icfAttachement->guessExtension();
                $icfAttachement->move($this->getParameter('uploads_folder'), $icfFileName);
                $icf->setAttachment($icfFileName);
            }

            $entityManager->persist($icf);
            $entityManager->flush();

            return $this->redirectToRoute(
                'application_show',
                ["id" => $application->getId()],
                Response::HTTP_SEE_OTHER
            );
        }
        ///
        return $this->render('sero/application/details.html.twig', [
            'appfeedbfrom' => $feedbackForm->createView(),
            'ammendmentForm' => $ammendmentForm->createView(),
            'irb_review_checklist_group' => $irb_review_checklist_group,
            'form' => $versionForm,
            'contuoationForm' => $contuoationForm,
            'decisions' => $decisions,
            'icfForm' => $icfForm,
            'application' => $application,
            'versions' => array_reverse($existingVersion),
        ]);
    }

    #[Route('/{id}/addicf', name: 'add_icf', methods: ['POST'])]
    public function addicf(Request $request, SEROHelper $seroHelper, EntityManagerInterface $entityManager, Application $application): Response
    {
        $icf = new Icf;
        $icfForm = $this->createForm(IcfType::class, $icf);
        $icfForm->handleRequest($request);

        if ($icfForm->isSubmitted() && $icfForm->isValid()) {
            $icf->setApplication($application);
            $icf->setCreatedAt(new \DateTime());
            $icf->setVersionNumber($seroHelper->icfVersion($application));
            dd($icfForm->get('attachment')->getData());
            if ($icfForm->get('attachment')->getData()) {
                $ver = $icfForm->getData();
                $icfAttachement = $icfForm->get('attachment')->getData();
                $icfFileName = $seroHelper->icfFileNamer($ver) . $icfAttachement->guessExtension();
                $icfAttachement->move($this->getParameter('uploads_folder'), $icfFileName);
                $icf->setAttachment($icfFileName);
            }

            $entityManager->persist($icf);
            $entityManager->flush();

            return $this->redirectToRoute(
                'application_show',
                ["id" => $application->getId()],
                Response::HTTP_SEE_OTHER
            );
        }
        ///
        return $this->render('sero/application/details.html.twig', [
            'icfForm' => $icfForm,
            'application' => $application,
        ]);
    }

    #[Route('/{id}/export',   name: 'export', methods: ['GET', 'POST'])]

    public function exportnow(EntityManagerInterface $em, Application $uid)
    {


        $submission = $em->getRepository(Application::class)->findOneBy(['id' => $uid->getId()]);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        $data = file_get_contents('files/site_setting/ephi2.jpg');
        $type = 'png';
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $pdfOptions->set('tempDir', '/home/cornerstone/tmp');
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->set_option("isPhpEnabled", true);
        $html = $this->renderView('sero/application/cert2.html.twig', [
            'user' => $this->getUser(),
            'orglogos' => $base64,
            // 'application' => $app,
            'base64' => $base64,
            'application' => $submission,
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
        $font = null;
        $dompdf->getCanvas()->page_text(72, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0, 0, 0));
        ob_end_clean();
        $filename = $submission->getTitle();
        $dompdf->stream($filename . "file.pdf", [
            "Attachment" => false,
        ]);
    }
    /////
    #[Route('/{filename}/download',   name: 'download', methods: ['POST'])]
    public function download(Request $request, $filename)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (!$request) {
            $this->addFlash("danger", "The requested file could not be found!");
        }
        if ($filename) {

            return $this->file($this->getParameter('uploads_folder') . '/' . $filename);
        } else {
            $this->addFlash("danger", "The requested file could not be found!");
        }
    }


    #[Route('/{id}/edit', name: 'application_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Application $application, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('application_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/application/edit.html.twig', [
            'application' => $application,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/{dec}', name: 'initial_decision', methods: ['GET', 'POST'])]
    public function decide(DecisionType  $dec, Version $version, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $version->setDecision($dec);
        $entityManager->persist($version);
        $entityManager->flush();
        $this->addFlash("success", "The Protocol version got  " . $dec->getName() . " decision !");

        return $this->redirectToRoute(
            'application_show',
            ["id" => $version->getApplication()->getId()],
            Response::HTTP_SEE_OTHER
        );
        // return $this->redirectToRoute('application_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'application_delete', methods: ['POST'])]
    public function delete(Request $request, Application $application, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$application->getId(), $request->getPayload()->get('_token'))) {
            $application->setStatus('withdrawn');
            $entityManager->persist($application);
            $entityManager->flush();
        $this->addFlash("success", "The Protocol has been withdrawn!");

        }

        return $this->redirectToRoute('application_index', [], Response::HTTP_SEE_OTHER);
    }
}
