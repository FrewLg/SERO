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
use Dompdf\Options;
// Reference the Font Metrics namespace 
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
        $file= $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/files/site_setting/ephi.png');
        $reviewAssignment = $entityManager->getRepository(ReviewAssignment::class)->findBy(['application' => $app->getId()]);
        $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();
 
        $image = "http://127.0.0.1:8008/files/site_setting/ephi.png";

        $data = [
            // 'imageSrc'  => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/cert_templates/21.jpg'),
            'title'         => $app->getTitle(),
            'irb_review_checklist_group' => $irb_review_checklist_group,
            'review_assignment' => $reviewAssignment,
            'appdetail'=>$app,
            'application' => $app,
            'orglogos' => $image,
            'orgdlogos'        => $this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/files/site_setting/ephi.png'),
             
        ];
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set("isPhpEnabled", true);
        $pdfOptions->set('tempDir', '/tmp');
        $pdfOptions->setIsHtml5ParserEnabled(true);
        // $html = ob_get_clean();
        $html =  $this->renderView('sero/application/cert2.html.twig', $data);
        $dompdf = new Dompdf($pdfOptions);
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
$x = (($w-$textWidth)/2); 
$y = (($h-$txtHeight)/2); 
$canvas->text($x, $y, $text, $font, size:36, color:[0, 0, 0], word_space:0.0, char_space:0.0, angle:-45);
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

        return new Response (
            $dompdf->stream('SERO-Ethical Clearance Certificate', ["Attachment" => true]),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
 
    }
    private function imageToBase64($path) {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
