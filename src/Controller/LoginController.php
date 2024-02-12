<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        // if(true){
        //     return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
        // }
        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
                ]);
    }

    #[Route('/', name: 'public_homepage')]
    public function home(AuthenticationUtils $authenticationUtils): Response
    {

       $mess= "Welcome";
        return $this->render('homepage/index.html.twig', [
            'msg' => $mess,
                 ]);
    }
}
