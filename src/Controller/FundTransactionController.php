<?php

namespace App\Controller;

use App\Entity\Fund;
use App\Entity\FundTransaction;
use App\Form\FundTransactionType;
use App\Form\FundType;
use App\Repository\FundRepository;
// use Skies\SkiesQRcodeBundle\Generator\Generator;
use App\Repository\FundTransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\Builder\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Skies\SkiesQRcodeBundle\Generator\Generator;
use Endroid\QrCode\Builder\BuilderInterface;
use Skies\QRcodeBundle\Generator\Generator as QRGenerator;
use Symfony\Bundle\MakerBundle\Generator as MakerBundleGenerator;

#[Route('/fundtrans')]
class FundTransactionController extends AbstractController
{
 
        
    #[Route('/{id}/transactions', name: 'app_fund_transactions', methods: ['GET'])]
    public function index(FundTransactionRepository $fundTransactionRepository, Fund $fund
    ,FundRepository $fundepository,
    
    ): Response
    {
        $fundname =  $fundepository->findOneBy(['id'=>$fund->getId()]);
        return $this->render('fund_transaction/index.html.twig', [
            'fundName'=>$fundname,
            // 'fundName'=>$fundname
            'fund_transactions' => $fundTransactionRepository->findBy(['fundName'=>$fund->getId()]),
        ]);
    }

    #[Route('/all', name: 'app_fund_transaction_index', methods: ['GET'])]
    public function alltr(FundTransactionRepository $fundTransactionRepository): Response
    {
        return $this->render('fund_transaction/index.html.twig', [
            'fundName'=>"All",
            'fund_transactions' => $fundTransactionRepository->findAll(),
        ]);
    }


    #[Route('/{id}/ded', name: 'app_fund_transaction_new', methods: ['GET', 'POST'])]
    public function transact(Request $request, Fund $fundname, 
    FundTransactionRepository $fundTransactionRepository,
        EntityManagerInterface $entityManager): Response
    {
        $fundTransaction = new FundTransaction();
        $form = $this->createForm(FundTransactionType::class, $fundTransaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $alltransactions =  $fundTransactionRepository->findBy(['fundName'=>$fundname->getId()]);

            $availablefund=$fundname->getBudget()- $fundTransaction->getDeducted();
             
                $sum = 0;
                foreach ($alltransactions as $number) {
                    $sum += $number->getDeducted();
                }
                echo $sum;

            if($fundTransaction->getDeducted()>$fundname->getBudget()-$sum){

                $this->addFlash("danger", "Available fund is less than you have requested !");
                return $this->redirectToRoute('app_fund_transaction_new', ['id'=>$fundname->getId()], Response::HTTP_SEE_OTHER);
           
            }
            $fundTransaction->setReferenceNubmer("ETF". $fundTransaction->getId().rand(1,99999));
            $fundTransaction->setCurrentBalance( $availablefund);
            // $fundname->setAvailableBudget($availablefund);
            $fundTransaction->setDeductedAt(new \Datetime());
            $fundTransaction->setCreatedBy($this->getUser());
           
            $entityManager->persist($fundTransaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_fund_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fund_transaction/new.html.twig', [
            'fund_transaction' => $fundTransaction,
            'form' => $form, 
        ]);
    }


    #[Route('/{id}/recpt', name: 'app_fund_transddactions', methods: ['GET'])]
    public function qrc(FundTransaction $fundTransaction ): Response
    {

        $srringToGenerate=$fundTransaction->getReferenceNubmer();
        $options = array(
            'code'   => $srringToGenerate,
            'type'   => 'qrcode',
            'format' => 'html',
        );            
        $generator = new QRGenerator();
        $barcode = $generator->generate($options);          
        return $this->render('fund_transaction/show.html.twig', [
    //         'fund_transaction' => $fundTransaction->findBy(['id'=>$fundTransaction->getId()]),
            'barcode' => $barcode,
    //         // ''
        ]);
    }

    #[Route('/{id}', name: 'app_fund_transaction_show', methods: ['GET'])]
    public function show(FundTransaction $fundTransaction ): Response
    {

        $srringToGenerate=$fundTransaction->getReferenceNubmer();
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

        return $this->render('fund_transaction/show.html.twig', [
            'fund_transaction' => $fundTransaction,
            'barcode' => $barcode,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fund_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FundTransaction $fundTransaction
    ,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FundTransactionType::class, $fundTransaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_fund_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fund_transaction/edit.html.twig', [
            'fund_transaction' => $fundTransaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/statement', name: 'statement', methods: ['GET','POST'])]

    public function statement(FundTransactionRepository $fundTransactionRepository, Fund $fund
    ,FundRepository $fundepository ) {
 
            // $thestatement = $fundTransactionRepository->find(['fundName' => $fundepository->getId()]);
            $thestatement =  $fundTransactionRepository->findOneBy(['fundName'=>$fund->getId()]);

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
            $srringToGenerate=$thestatement->getFundName();
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

        $fundname =  $fundepository->findOneBy(['id'=>$fund->getId()]);

        $html = $this->renderView('fund_transaction/statement.html.twig', [
                
               'orglogos'=>$orglogos,    
             'twiglogo' => $site_logo,
             'barcode' => $barcode,
             'fundName'=>$fundname,
             // 'fundName'=>$fundname
             'fund_transactions' => $fundTransactionRepository->findBy(['fundName'=>$fund->getId()]),
         
              
        ]);
         $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
 
        ob_end_clean();
        $filename = "mee";

        $dompdf->stream($filename . "- statement.pdf", [
            "Attachment" => true,
        ]);
    }


}
