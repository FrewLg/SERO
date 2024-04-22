<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

// #[Route('/cert', name: 'app_pdf_generator')]
#[Route('{_locale<%app.supported_locales%>}/cert')]

class PdfGeneratorController extends AbstractController
{
    #[Route('/', name: 'app_pdf_generator')]
    public function index()
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $data = [
            // 'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/cert_templates/21.jpg'),
            'name'         => 'John Doe',
            'address'      => 'USA',
            'mobileNumber' => '000000000',
            'orglogos'=>"http://127.0.0.1:8008/files/site_setting/ephi.png",
            'twiglogo'        => 'http://127.0.0.1:8008/public/cert_templates/21.jpg',
            'email'        => 'test@da.sa'
        ];
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('tempDir', '/tmp');
        $pdfOptions->setIsHtml5ParserEnabled(true);
       
        $html =  $this->renderView('training_participant/cert2.html.twig', $data);
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->render();
        // ob_end_clean();
        //  dd();
 
        return new Response (
            $dompdf->stream('resume', ["Attachment" => true]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
 
    }
    // private function imageToBase64($path) {
    //     $path = $path;
    //     $type = pathinfo($path, PATHINFO_EXTENSION);
    //     $data = file_get_contents($path);
    //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    //     return $base64;
    // }
}
