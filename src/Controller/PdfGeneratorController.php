<?php

namespace App\Controller;

use App\Entity\SERO\Application;
use App\Entity\SERO\ReviewAssignment;
use App\Entity\SERO\ReviewChecklistGroup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Symfony\Component\DomCrawler\Image;
use Intervention\Image\ImageManager;
use Skies\QRcodeBundle\Generator\Generator as QRGenerator;
use Dompdf\Options;
use Dompdf\FontMetrics;

#[Route('{_locale<%app.supported_locales%>}/cert')]

class PdfGeneratorController extends AbstractController
{

    /**
     * Writes text at the specified x and y coordinates.
     *
     * @param float $x
     * @param float $y
     * @param string $text the text to write
     * @param string $font the font file to use
     * @param float $size the font size, in points
     * @param array $color
     * @param float $word_space word spacing adjustment
     * @param float $char_space char spacing adjustment
     * @param float $angle angle
     */

    #[Route('/{id}/', name: 'ethical_clearance_cert')]
    public function index(Application $app, EntityManagerInterface $entityManager)
    { 

        $this->denyAccessUnlessGranted('ROLE_USER');
        // $file = $this->imageToBase64($this->getParameter('kernel.project_dir') . "/uploads/files/site_setting/ephi.png");
        $reviewAssignment = $entityManager->getRepository(ReviewAssignment::class)->findBy(['application' => $app->getId()]);
        $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();
        // $image = "https://ephi.gov.et/wp-content/uploads/2021/03/ephi-logo-name-new-3.png";
        // $fullLocalpath = $this->getParameter('kernel.project_dir')."/uploads/files/site_setting/ephi.png";
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('dpi','120');
        $pdfOptions->set("isPhpEnabled", true);
        $pdfOptions->set('tempDir', '/tmp');
        $pdfOptions->set("isHtml5ParserEnabled", true);
        ob_get_clean();
        //barcode
        $srringToGenerate = "CERT" . $app->getTitle();
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

        $image = $this->getParameter('project_dir')."uploads/files/site_setting/ephi2.jpg";

        // Read image path, convert to base64 encoding
        $imageData = base64_encode(file_get_contents($image));

        // Format the image SRC:  data:{mime};base64,{data};
        $src = 'data:'.mime_content_type($image).';base64,'.$imageData;


        $data = [
            // 'orglogos'  => $this->imageToBase64($image), //->imageToBase64("http://127.0.0.1:8008/files/site_setting/ephi.png"),
            'title'         => $app->getTitle(),
            'irb_review_checklist_group' => $irb_review_checklist_group,
            'review_assignment' => $reviewAssignment,
            'appdetail' => $app,
            'application' => $app,
            'orglogos' => $src,
            // 'orglogos' => base64_encode(file_get_contents($this->getParameter('site_setting'))),
            // 'orglogos'=> $this->imageToBase64($this->getParameter('site_setting')),
            // 'orglogos' =>  '<img src="data:image/png;base64, '.$this->imageToBase64($image).'">',

        ];
        $html =  $this->renderView('sero/application/cert2.html.twig', $data);
        // $html =  $this->renderView('sero/application/app_sections/certnew.html.twig', $data);
        $dompdf = new Dompdf(array('enable_remote' => true));
        $dompdf->set_option("isPhpEnabled", true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4", "landscape");
        $dompdf->render();
        // Instantiate canvas instance
        $canvas = $dompdf->getCanvas();
        $fontMetrics = new FontMetrics($canvas, $pdfOptions);
        $w = $canvas->get_width();
        $h = $canvas->get_height();
        $font = $fontMetrics->getFont('sans');
        $text = "Ethical Clearance";
        $txtHeight = $fontMetrics->getFontHeight($font, 2);
        $textWidth = $fontMetrics->getTextWidth($text, $font, 2);
        $canvas->set_opacity(.2);
        $x = (($w - $textWidth) / 2);
        $y = (($h - $txtHeight) / 2);
        $canvas->text($x, $y, $text, $font, size: 36, color: [0, 0, 0], word_space: 0.0, char_space: 0.0, angle: -45);
        // $canvas = $dompdf->getCanvas();
        // Get height and width of page
        // $w = $canvas->get_width();
        // $h = $canvas->get_height();
        // Specify watermark image
        // $imageURL = $this->getParameter('kernel.project_dir').'/public/files/profile_pictures/defaultuser.png'; 
        // $imgWidth = 200;
        // $imgHeight = 20;
        // $canvas->set_opacity(.5);
        // $x = (($w-$imgWidth)/2);
        // $y = (($h-$imgHeight)/2);
        // $canvas->image($imageURL, $x, $y, $imgWidth, $imgHeight); 

        return new Response($dompdf->stream('SERO-Ethical Clearance Certificate', ["Attachment" => 1]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
    
    private function imageToBase64($path)
    {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }


    #[Route('/{id}/statement', name: 'statement', methods: ['GET'])]
    public function statement(Application $app)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        // $orglogos="http://127.0.0.1:8008/files/site_setting/ephi.png";
        // $site_logo = 'http://127.0.0.1:8008/cert_templates/21.jpg';
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('tempDir', '/tmp');
        $pdfOptions->setIsHtml5ParserEnabled(true);
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->set_option("isPhpEnabled", true);
        $srringToGenerate = "CERT" . $app->getTitle();
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

        $fundname =  "name";
        ob_get_clean();

        $html = $this->renderView('fund_transaction/statement.html.twig', [

            //    'orglogos'=>$orglogos,
            //  'twiglogo' => $site_logo,
            'barcode' => $barcode,
            'fundName' => $fundname,
            'fund_transactions' => 'dsd',


        ]);
        $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // ob_end_clean();
        $filename = "mee";

        $dompdf->stream($filename . "- statement.pdf", [
            "Attachment" => false,
        ]);
    }
}