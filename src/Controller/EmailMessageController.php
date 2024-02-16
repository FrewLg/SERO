<?php

namespace App\Controller;

use App\Entity\EmailMessage;
use App\Form\EmailMessageType;
use App\Repository\EmailMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/email/message')]
class EmailMessageController extends AbstractController
{
    #[Route('/', name: 'app_email_message_index', methods: ['GET'])]
    public function index(EmailMessageRepository $emailMessageRepository): Response
    {
        return $this->render('email_message/index.html.twig', [
            'email_messages' => $emailMessageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_email_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $emailMessage = new EmailMessage();
        $form = $this->createForm(EmailMessageType::class, $emailMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emailMessage);
            $entityManager->flush();

            return $this->redirectToRoute('app_email_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('email_message/new.html.twig', [
            'email_message' => $emailMessage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_email_message_show', methods: ['GET'])]
    public function show(EmailMessage $emailMessage): Response
    {
        return $this->render('email_message/show.html.twig', [
            'email_message' => $emailMessage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_email_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmailMessage $emailMessage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmailMessageType::class, $emailMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_email_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('email_message/edit.html.twig', [
            'email_message' => $emailMessage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_email_message_delete', methods: ['POST'])]
    public function delete(Request $request, EmailMessage $emailMessage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emailMessage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($emailMessage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_email_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
