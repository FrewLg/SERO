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
    #[Route('/login/', name: 'app_login')]
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

    // #[Route('/home', name: 'public_homepage')]
    // public function home(Request $request): Response
    // {

    //     $locale = $request->getLocale();
    //    $mess= "Welcome";
    //     return $this->render('homepage/index.html.twig', [
    //         'msg' => $mess,
    //         'locale' => $locale,
    //              ]);
    // }
    #[Route('/{locale}/d', name: 'change_locale', methods:['GET','POST'])]
    public function localechange(Request $request )
    {

//         $locale = $request->getLocale();
//         // $request = $event->getRequest();

// //     // some logic to determine the $locale
//     $request->setLocale($locale);
$locale = $request->getLocale();
$mess= "Welcome";
return $this->render('homepage/index.html.twig', [
    'msg' => $mess,
    'locale' => $locale,
         ]);
    }
 

}
