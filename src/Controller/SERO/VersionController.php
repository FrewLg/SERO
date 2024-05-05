<?php

namespace App\Controller\SERO;

use App\Entity\SERO\Version;
use App\Form\SERO\VersionType;
use App\Repository\SERO\VersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('{_locale<%app.supported_locales%>}/protocol-version')]
class VersionController extends AbstractController
{
    #[Route('/', name: 'version_index', methods: ['GET'])]
    public function index(VersionRepository $versionRepository): Response
    {
        return $this->render('sero/version/index.html.twig', [
            'versions' => $versionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'version_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $version = new Version();
        $versionForm = $this->createForm(VersionType::class, $version);
        $versionForm->handleRequest($request);

        if ($versionForm->isSubmitted() && $versionForm->isValid()) {
            if ($versionForm->get('attachement')->getData()) {
                $versionAttachement = $versionForm->get('attachement')->getData();

                $versionfile_name = 'Version' . md5(uniqid()) . '.' . $versionAttachement->guessExtension();
                $versionAttachement->move($this->getParameter('uploads_folder'), $versionfile_name);
                $version->setAttachment($versionfile_name);
            }
            $entityManager->persist($version);
            $entityManager->flush();

            return $this->redirectToRoute('version_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/version/new.html.twig', [
            'version' => $version,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'version_show', methods: ['GET'])]
    public function show(Version $version): Response
    {
        return $this->render('sero/version/show.html.twig', [
            'version' => $version,
        ]);
    }

    #[Route('/{id}/edit', name: 'version_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Version $version, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VersionType::class, $version);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('version_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/version/edit.html.twig', [
            'version' => $version,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'version_delete', methods: ['POST'])]
    public function delete(Request $request, Version $version, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $version->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($version);
            $entityManager->flush();
        }

        return $this->redirectToRoute('version_index', [], Response::HTTP_SEE_OTHER);
    }
}
