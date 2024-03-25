<?php

namespace App\Controller;

use App\Entity\TrainingParticipant;
use App\Form\TrainingParticipantType;
use App\Repository\TrainingParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Skies\QRcodeBundle\Generator\Generator as QRGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('{_locale<%app.supported_locales%>}/enrollement')]
class TrainingParticipantController extends AbstractController
{
    #[Route('/', name: 'app_training_participant_index', methods: ['GET'])]
    public function index(TrainingParticipantRepository $trainingParticipantRepository): Response
    {
        return $this->render('training_participant/index.html.twig', [
            'training_participants' => $trainingParticipantRepository->findAll(),
        ]);
    }

   

    

    #[Route('/new', name: 'app_training_participant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingParticipant = new TrainingParticipant();
        $form = $this->createForm(TrainingParticipantType::class, $trainingParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trainingParticipant);
            $entityManager->flush();

            return $this->redirectToRoute('app_training_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_participant/new.html.twig', [
            'training_participant' => $trainingParticipant,
            'form' => $form,
        ]);
    }

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
   
    #[Route('/{id}/cert', name: 'show_my_cert', methods: ['GET','POST'])]

    public function exportcertnow(EntityManagerInterface $entityManager
    , TrainingParticipantRepository $trparep, TrainingParticipant $uid) {
 
            $submission = $trparep->findOneBy(['id' => $uid]);

            // $webRoot = '/home/cornerstone/Desktop/projects/ntcms/';
            $orglogos="http://127.0.0.1:8000/files/site_setting/ephi.png";
            $site_logo = 'http://127.0.0.1:8000/cert_templates/21.jpg';
            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            $pdfOptions->set('isRemoteEnabled', true);
            $pdfOptions->set('tempDir', '/tmp');
            $pdfOptions->setIsHtml5ParserEnabled(true);
            $dompdf = new Dompdf($pdfOptions);
            $dompdf->set_option("isPhpEnabled", true);
            // $dompdf->setBasePath($webRoot);
            $srringToGenerate=$submission->getTraining()->getTrainingRequest()->getTrainingTopic();
            $options = array(
                'code'   => $srringToGenerate,
                'type'   => 'qrcode',
                'format' => 'html',
                'code'   => 'string to encode',
                // 'type'   => 'datamatrix',
                'format' => 'png',
                'width'  => 10,
                'height' => 10,
                'color'  => array(127, 127, 127),
            );
        $generator = new QRGenerator();
        $barcode = $generator->generate($options);

        // $logos=$this->getParameter('certtemplates');
        // $site_logo=$this->params->get('site_logo');


        $html = $this->renderView('training_participant/cert.html.twig', [
             'name' => "Frew Oee",
             'desc' => "This is to certify",
             'type' => "online ",      'orglogos'=>$orglogos,         'twiglogo' => $site_logo,
             'date'=> $submission->getCertIssuedAt(),
            //  'about' => $submission->getTrainings()->getTrainingRequest()->getTrainingTopic()->getName(),
             'college' => "about" ,
            'barcode' => $barcode,
             'about' => "about" ,
             'training' => $submission ,
            //  'name' => $submission->getParticipant()->getUserInfo(),
            //  'desc' => $submission->getTraining()->getDescription(),
            //  'type' => $submission->getTraining()->getTrainingType(),
            //  'date'=> $submission->getTraining()->getCreatedAt(),
            //  'about' => $submission->getTraining()->getName(),
            //  'training' => $submission->getTraining() ,
              
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
 
        ob_end_clean();
        $filename = "mee";

        $dompdf->stream($filename . "- certificate.pdf", [
            "Attachment" => true,
        ]);
    }


    #[Route('/{id}', name: 'app_training_participant_show', methods: ['GET'])]
    public function show(TrainingParticipant $trainingParticipant): Response
    {
        return $this->render('training_participant/show.html.twig', [
            'training_participant' => $trainingParticipant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingParticipant $trainingParticipant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingParticipantType::class, $trainingParticipant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_training_participant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_participant/edit.html.twig', [
            'training_participant' => $trainingParticipant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_participant_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingParticipant $trainingParticipant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainingParticipant->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trainingParticipant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_participant_index', [], Response::HTTP_SEE_OTHER);
    }
}
