<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ErrorController extends AbstractController
{

    #[Route('/error', name: 'app_error')]
    public function show(\Throwable $exception, Request $request ): Response
    {
                
        return $this->render('bundles/TwigBundle/Exception/error500.html.twig', [
            'controller_name' => 'ErrorController',
            // 'exception' => new HttpException("d", $string),

        ]);
    }
}
