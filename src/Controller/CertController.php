<?php

namespace App\Controller;

use App\Entity\TrainingParticipant;
use App\Form\CertType;
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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[Route('{_locale<%app.supported_locales%>}/certificate')]
class CertController extends AbstractController
{
     
    #[Route('/', name: 'certificates', methods: ['GET', 'POST'])]
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

   
   
    #[Route('/verify/{id}',   name: 'verify_cert', methods: ['GET','POST'])]
    public function verify( Request $request, TrainingParticipantRepository $trparep, EntityManagerInterface $entityManager): Response
    {
        //  $form = $this->createForm(CertType::class );
        // $form->handleRequest($request);
        $form = $this->createFormBuilder()
        ->add('certId')
        ->getForm();
        // $cert =null;
        if ($form->isSubmitted() && $form->isValid()) {
 
            $id = $form->get('certId')->getData();
            $cert = $trparep->findOneBy(['certId' => $id]);
            if ($cert ) {
            $this->addFlash("success", "Certificate found  successflly !");
            $certfound=$cert ; 
            return $this->render('training_participant/verify.html.twig', [
                'form' => $form,
                'training_participant' => $cert ,
            ]);          }
            else{
            $this->addFlash("danger", "Certificate has never been issued for the code you have provided !");
            return $this->redirectToRoute('verify_cert', [], Response::HTTP_SEE_OTHER);

            }
        } 
        return $this->render('training_participant/verify.html.twig', [
            'form' => $form,
            // 'training_participant' => $cert ,
        ]);
    }
    
    #[Route('/irb-clearance/{certificateCode}', name: 'irb_validate2')]
    #[Route('/irb-clearance/', name: 'irb_validate')]
    public function certver(Request $request,IrbCertificate $irbCertificate=null,DomPrint $domPrint): Response
    {
        $em=$this->getDoctrine()->getManager();
        if($request->request->get('validate')){
           $irbCertificate= $em->getRepository(IrbCertificate::class)->findOneBy(['certificateCode'=>$request->request->get('validate')]);
            if(!$irbCertificate){
                 $this->addFlash('danger','No IRB clearance was issued with "'
                 .$request->request->get('validate').'" code');
                
                }

            else{

                $this->addFlash('success',' IRB ethical clearance certificate  found "');
               
                return $this->render('irb/clearance.html.twig', [
                    'irb'=>$irbCertificate
                ]);
            }
        }
        if($request->query->get('export')){
            if($irbCertificate->getIrbApplication()->getSubmittedBy() != $this->getUser()){
               return new AccessDeniedHttpException();
            }
           return  new Response($domPrint->print("irb/print.html.twig",[
            "certificate"=>$irbCertificate],
            "PRINT",DomPrint::ORIENTATION_PORTRAIT,DomPrint::PAPER_A4,true));
        }

        return $this->render('irb/clearance.html.twig', [
           
        ]);
    }

    #[Route('/certificateas',   name: 'certificateas', methods: ['GET'])]
    public function all(TrainingParticipant $trainingParticipant): Response
    {
        return $this->render('training_participant/show.html.twig', [
            'training_participant' => $trainingParticipant,
        ]);
    }
   
}
