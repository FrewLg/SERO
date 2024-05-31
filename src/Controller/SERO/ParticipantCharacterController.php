<?php

namespace App\Controller\SERO;

use App\Entity\SERO\ParticipantCharacter;
use App\Form\SERO\ParticipantCharacterType;
use App\Repository\SERO\ParticipantCharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/s/e/r/o/participant/character')]
class ParticipantCharacterController extends AbstractController
{
    #[Route('/', name: 'app_s_e_r_o_participant_character_index', methods: ['GET'])]
    public function index(ParticipantCharacterRepository $participantCharacterRepository): Response
    {
        return $this->render('sero/participant_character/index.html.twig', [
            'participant_characters' => $participantCharacterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_s_e_r_o_participant_character_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $participantCharacter = new ParticipantCharacter();
        $form = $this->createForm(ParticipantCharacterType::class, $participantCharacter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participantCharacter);
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_participant_character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/participant_character/new.html.twig', [
            'participant_character' => $participantCharacter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_participant_character_show', methods: ['GET'])]
    public function show(ParticipantCharacter $participantCharacter): Response
    {
        return $this->render('sero/participant_character/show.html.twig', [
            'participant_character' => $participantCharacter,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_e_r_o_participant_character_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParticipantCharacter $participantCharacter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipantCharacterType::class, $participantCharacter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_s_e_r_o_participant_character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/participant_character/edit.html.twig', [
            'participant_character' => $participantCharacter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_e_r_o_participant_character_delete', methods: ['POST'])]
    public function delete(Request $request, ParticipantCharacter $participantCharacter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$participantCharacter->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($participantCharacter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_s_e_r_o_participant_character_index', [], Response::HTTP_SEE_OTHER);
    }
}
