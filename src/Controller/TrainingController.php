<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Training; 
use App\Entity\TrainingRequest;
use App\Entity\Directorate;
use App\Entity\TrainingParticipant;
use App\Entity\User;
use Symfony\Component\Translation\TranslatableMessage;
use App\Form\TrainingType;
use App\Repository\CouponRepository;
use App\Repository\DirectorateRepository;
use App\Repository\EmailMessageRepository;
use App\Repository\TrainingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Length;

#[Route('{_locale<%app.supported_locales%>}/training')]
class TrainingController extends AbstractController
{
    #[Route('/', name: 'app_training_index', methods: ['GET'])]
    public function index(TrainingRepository $trainingRepository): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // if ($this->get('security.context')->isGranted('ROLE_SADMIN')) {
        // }
       
        // $this->denyAccessUnlessGranted('ROLE_ADMINs', null, 'Unable to access this page :( you are not admin');
        return $this->render('training/index.html.twig', [
            'trainings' => $trainingRepository->findAll(),
        ]);
    }

    #[Route('/{id}/r', name: 'details', methods: ['GET'])]
    public function details(TrainingRepository $trainingRepository): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->get('security.context')->isGranted('ROLE_SADMIN')) {
            // Execute some php code here
         }
        return $this->render('training/index.html.twig', [
            'trainings' => $trainingRepository->findAll(),
        ]);
    }

    public function __construct(
        private TrainingRepository $tr,
        private UrlGeneratorInterface $router
    )
    {}
    
    #[Route('/schedule', name: 'training_calendar', methods: ['GET'])]
    public function calendar(TrainingRepository $trainingRepository): Response
    { 
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('training/calendar.html.twig', [
            'trainings' => $trainingRepository->findAll(),
        ]);
    }


    #[Route('/{id}/add', name: 'app_training_add_details', methods: ['GET', 'POST'])]
    public function adddata(Request $request, TrainingRequest $trainingrequest, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if(!$trainingrequest->getTraining()){
                     $training = new Training();
        }
        else
        {
            $training =   $trainingrequest->getTraining();
        }
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training->setCreatedAt(new \Datetime());
            $training->setTrainingRequest($trainingrequest);
            $entityManager->persist($training);
            $entityManager->flush();
            // $message = new TranslatableMessage('Details added successflly!');

            $this->addFlash("success", "Details added successflly !");
            return $this->redirectToRoute('details', ['id'=>$training->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('training/new.html.twig', [
            'training' => $training,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_show', methods: ['GET'])]
    public function show(Training $training, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // $this->coupons($training, $em);
        return $this->render('training/show.html.twig', [
            'training' => $training,
        ]);
    }
   
    
    #[Route('/{id}/coupons', name: 'tr_coupon', methods: ['GET'])]
    public function trainingcoupons(Training $training, UserRepository $userRepository,  PaginatorInterface $paginator,  CouponRepository $couponRepository, DirectorateRepository $directorateRepository, Request $request): Response
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


 

 #[Route('/{id}/sendbatch', name: 'sendbatch_email', methods: ['GET', 'POST'])]

public function sendbatch(Training $training,  MailerInterface $mailer, EmailMessageRepository $ema): Response {
  
    $messages = $ema->findOneBy(['email_key' => 'TRAINING_ANNOUNCEMENT']);
    $subject = $messages->getSubject();
    $body = $messages->getBody();
    
    ////////////
     
        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@ephi.gov.et', 'SNTC'))
            //    ->to($theEmails)
            ->to(new Address('frew.legese@gmail.com', "Firra"))
            // ->bcc(new Address($theEmails[$i], $theFirstNames[$i]))
            ->subject($subject)
            ->htmlTemplate('emails/news.html.twig')
            ->context([
                'subject' => $subject,
                'body' => $body.$training->getTrainingRequest()->getTrainingTopic(),
                'name' => "fREE",
                'Authoremail' => "firewlegese74@gmail.com",
            ]);
        $mailer->send($email);
     
    $this->addFlash("danger", "Email sent to short listed porposal PIs successfully!");
    //////////////////////////// end emailing ///////////////////////
    return $this->redirectToRoute('app_training_index');
}

    #[Route('/{id}/gencoupons', name: 'generate_coupons', methods: ['GET', 'POST'])]
    public function gencoupons( Training $training, EntityManagerInterface $entityManager ): Response
    {
        
        
                $allUser = $entityManager->getRepository(User::class)->findAll();
                $directorateRepository = $entityManager->getRepository(Directorate::class)->findAll();
                $totalPossiblecoupons=   1 + $training->getTrainingRequest()->getNumberOfParticipants();
                $thisYear =  date_format(new \DateTime(''),"y"); 
                $allusers=count($allUser);
                $alldirectorates=  $directorateRepository; 
                foreach ($alldirectorates as  $value) {
                $depEmp=count($value->getUsers());

                $deppec= $depEmp*100/$allusers;
                $depcuota= $deppec*$totalPossiblecoupons/100; 
                    for ($i=0; $i < $depcuota ; $i++) { 
                $excluded=$training->getTrainingRequest();
                    // if (!$training->getTrainingRequest()->getInclusions())  
                    // { 
                    //     continue; 
                    // } 
                // dd($excluded);
                        $coupon = new Coupon(); 
                        $randomCoupon=rand(1, 10+$totalPossiblecoupons);  
                        $randomCoupon2=rand(1, 95);  
                        $coupon->setTraining( $training);
                        $coupon->setCreatedAt(new \Datetime());
                        $coupon-> setCouponNumber($randomCoupon2.$randomCoupon."-".$value->getAcronym().$thisYear."");
                        $coupon->setDirectorate($value );
                        $entityManager->persist($coupon); 
                        $entityManager->flush(); 
            }
            }
        return $this->redirectToRoute('tr_coupon', ['id'=>$training->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_training_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Training $training, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training/edit.html.twig', [
            'training' => $training,
            'form' => $form,
        ]);
    }

  
    #[Route('/{id}/cert', name: 'cert', methods: ['GET','POST'])]

    public function exportcertnow(Request $request, TrainingParticipant $uid) {
 
        $em = $this->getDoctrine()->getManager(); 
        $submission = $em->getRepository('App:TrainingParticipant')->findOneBy(['id' => $uid]);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('tempDir', '/tmp');
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->set_option("isPhpEnabled", true);

        $html = $this->renderView('training/cert.html.twig', [
             'name' => $submission->getParticipant()->getUserInfo(),
             'desc' => $submission->getTraining()->getDescription(),
             'type' => $submission->getTraining()->getTrainingType(),
             'date'=> $submission->getTraining()->getCreatedAt(),
             'about' => $submission->getTraining()->getName(),
             'training' => $submission->getTraining() ,
              
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // $font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
        // $font = null;
        // $dompdf->getCanvas()->page_text(72, 18,  $font, 10, array(0, 0, 0));

        ob_end_clean();
        $filename = $submission->getParticipant();

        $dompdf->stream($filename . "- certificate.pdf", [
            "Attachment" => true,
        ]);
    }


    #[Route('/{id}', name: 'app_training_delete', methods: ['POST'])]
    public function delete(Request $request, Training $training, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$training->getId(), $request->request->get('_token'))) {
            $entityManager->remove($training);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
    }
}
