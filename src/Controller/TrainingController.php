<?php

namespace App\Controller;

use App\Entity\Coupon;
use App\Entity\Training;
use App\Form\CouponType;
use App\Form\TrainingType;
use App\Repository\DirectorateRepository;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;

#[Route('/training')]
class TrainingController extends AbstractController
{
    #[Route('/', name: 'app_training_index', methods: ['GET'])]
    public function index(TrainingRepository $trainingRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('training/index.html.twig', [
            'trainings' => $trainingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_training_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $training->setCreatedAt(new \Datetime());
            
            $entityManager->persist($training);
            $entityManager->flush();
            $this->addFlash("success", " created successflly !");

            return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training/new.html.twig', [
            'training' => $training,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_show', methods: ['GET'])]
    public function show(Training $training): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('training/show.html.twig', [
            'training' => $training,
        ]);
    }


    #[Route('/{id}/coupons', name: 'generate_coupons', methods: ['GET', 'POST'])]
    public function coupons(Request $request, Training $training,  DirectorateRepository $directorateRepository, EntityManagerInterface $entityManager): Response
    {
        
          //////////////////#####################check the call  deadline########################////////////////
        //   $deadline = $therequest->getDeadline();
        //   $today = new \DateTime();
        //   $message = '';
        //   if ($deadline <= $today) {
        //       $message = "Overdue!";
        //       #    echo $day;
        //   }
          //////
                $totalPossiblecoupons=   1 + $training->getTrainingRequest()->getNumberOfParticipants();
                $quotas=  3;
                // $training->getTrainingRequest()->getDirectorateQuota()->NumberOfParticipants();
                $thisYear =  date_format(new \DateTime(''),"y"); 
                //    dd($thisYear);
                $alldirectorates=  $directorateRepository->findAll();
                // $i=count($alldirectorates)*;
            foreach ($alldirectorates as  $value) {
                for ($i=0; $i < $totalPossiblecoupons; $i++) { 
            // $random_numbers = range(1, 10+$totalPossiblecoupons);
            // $randomCoupon= shuffle($random_numbers);
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
        return $this->redirectToRoute('app_coupon_index', [], Response::HTTP_SEE_OTHER);

        
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

       /**
     * @Route("/{id}/cert", name="cert", methods={"GET"})
     */
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
