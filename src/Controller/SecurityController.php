<?php

namespace App\Controller;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface as ExceptionTransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '{_locale<%app.supported_locales%>}/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }


     
    #[Route(path: '{_locale<%app.supported_locales%>}/mailtest', name: 'mailtest')]

     public function sendmailtest(MailerInterface $mailer): Response {
           
        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@wgu.edu.et', 'Test ice'))
            //    ->to($theEmails)
            ->to(new Address('frew.legese@gmail.com', "Test "))
             ->subject("Mailer test for symfony")
            ->htmlTemplate('emails/news.html.twig')
            ->context([
                'subject' => 'dsa',
                'body' => 'dsa',
                'name' => 'das',
                'Authoremail' => 'frew.legese@gmail.com',
            ]);
        $mailer->send($email);

    //         try{

    //     $mailer->send($email);
    // $this->addFlash("success","Email sent and Registered Successfully!!");

    // }
    //     catch(TransportExceptionInterface $e){

    //         $this->addFlash("danger", $e."Error occured. Please try again later !");
    //     }
        
return $this->redirectToRoute('app_training_index');



}

    
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
