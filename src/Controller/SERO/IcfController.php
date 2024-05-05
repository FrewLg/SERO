<?php

namespace App\Controller\SERO;

use App\Entity\SERO\Application;
use App\Entity\SERO\Icf;
use App\Form\SERO\IcfType;
use App\Helper\SEROHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IcfController extends AbstractController
{
    #[Route('/{id}/addddicf', name: 'adsdd_icf', methods:['POST'] )]
    public function addiscf(Request $request , SEROHelper $seroHelper, EntityManagerInterface $entityManager, Application $application): Response
    {
        $icf = new Icf;
        $icfForm = $this->createForm(IcfType::class, $icf);
        $icfForm->handleRequest($request);

        if ($icfForm->isSubmitted() && $icfForm->isValid()) {
            $icf->setApplication($application);
            $icf->setCreatedAt(new \DateTime());
            $icf->setVersionNumber($seroHelper->icfVersion($application));

            if ($icfForm->get('attachment')->getData()) {
                $ver = $icfForm->getData();
                $icfAttachement = $icfForm->get('attachment')->getData();
                $icfFileName = $seroHelper->icfFileNamer($ver).$icfAttachement->guessExtension();
                $icfAttachement->move($this->getParameter('uploads_folder'), $icfFileName);
                $icf->setAttachment($icfFileName);
            }

            $entityManager->persist($icf);
            $entityManager->flush();

            return $this->redirectToRoute('application_show', ["id" => $application->getId()],
            Response::HTTP_SEE_OTHER);
                }
        ///
        return $this->render('sero/application/details.html.twig', [
             'icfForm' => $icfForm,
            'application' => $application,
         ]);
    }
}
