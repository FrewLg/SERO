<?php

namespace App\Controller;

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
    #[Route('/', name: 'homes')]
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

}
