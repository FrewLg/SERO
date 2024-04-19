<?php

namespace App\Controller;

use App\Entity\SERO\IrbCertificate;
use Symfony\Component\Translation\LocaleSwitcher;
use App\Entity\TrainingParticipant;
use App\Form\TrainingParticipantType;
use App\Repository\TrainingParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    #[Route('{_locale<%app.supported_locales%>}/', name: 'homepage')]
    public function index(
        TranslatorInterface $translator, Request $request,
        LocaleSwitcher $localeSwitcher): Response
    {   
        // Set the active locale to German
        $localeSwitcher->setLocale('en'); 

        // Retrieve the active locale
        // $localeSwitcher->getLocale(); // => 'de'
	
        $locale = $request->getLocale();
           $mess= "Welcome";
            return $this->render('homepage/index.html.twig', [
                'msg' => $mess,
                'locale' => $locale,
                     ]);
    }

    #[Route('/irb-clearancdess/{certificateCode}', name: 'irb_validate2' )]
    #[Route('{_locale<%app.supported_locales%>}/irb-clearance/', name: 'irb_validate')]
    public function home(Request $request, EntityManagerInterface $em, IrbCertificate $irbCertificate=null ): Response
    {
        // $em=$this->getDoctrine()->getManager();
        if($request->request->get('validate')){
           $irbCertificate= $em->getRepository(IrbCertificate::class)->findOneBy(['certificateCode'=>$request->request->get('validate')]);
            if(!$irbCertificate){
                 $this->addFlash('danger','No IRB clearance was issued with "'
                 .$request->request->get('validate').'" code');
                
                }
             else{

                $this->addFlash('success',' IRB ethical clearance certificate  found "');
               
                return $this->render('sero/clearance.html.twig', [
                    'irb'=>$irbCertificate
                ]);
            }
        }
        // if($request->query->get('export')){
        //     if($irbCertificate->getIrbApplication()->getSubmittedBy() != $this->getUser()){
        //        return new AccessDeniedHttpException(); 
        //     }
        //    return  new Response($domPrint->print("sero/print.html.twig",["certificate"=>$irbCertificate],"PRINT",DomPrint::ORIENTATION_PORTRAIT,DomPrint::PAPER_A4,true));
        // }

        return $this->render('sero/clearance.html.twig', [
           
        ]);
    }

    #[Route('/verify-here/{certificateCode}', name: 'verify_by_link' , methods:['GET'])]
    public function clicktlinkoverify(  EntityManagerInterface $em,   $certificateCode ): Response
    {
         
           $irbCertificate= $em->getRepository(IrbCertificate::class)->findOneBy(['certificateCode'=>$certificateCode ]);
            if(!$irbCertificate){
                 $this->addFlash('danger','No IRB clearance was issued with the code: '.$certificateCode);
                
                }

            else{

                $this->addFlash('success','Valid IRB ethical clearance certificate   "');
               
                return $this->render('sero/clearance.html.twig', [
                    'irb'=>$irbCertificate
                ]);
            }
        
           return $this->render('sero/clearance.html.twig', [
           
        ]);
    }

    #[Route('{_locale<%app.supported_locales%>}/developers', name: 'developers' , methods:['GET'])]
    public function developers(   ): Response
    {
          
           return $this->render('sero/developers.html.twig', [
           
        ]);
    }

}
