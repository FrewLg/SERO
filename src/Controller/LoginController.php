<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    // #[Route('{_locale<%app.supported_locales%>}/login/', name: 'app_login')]
    // public function index(AuthenticationUtils $authenticationUtils): Response
    // {

    //     $error = $authenticationUtils->getLastAuthenticationError();

    //     $lastUsername = $authenticationUtils->getLastUsername();
        
    //     return $this->render('login/index.html.twig', [
    //         'last_username' => $lastUsername,
    //         'error'         => $error,
    //             ]);
    // }

    

}
