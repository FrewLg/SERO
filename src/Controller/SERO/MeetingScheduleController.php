<?php

namespace App\Controller\SERO;

use App\Entity\SERO\MeetingSchedule;
use App\Form\SERO\MeetingScheduleType;
use App\Repository\SERO\MeetingScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// #[Route('/s//meeting-schedule')]
#[Route('{_locale<%app.supported_locales%>}/meeting-schedule')]

class MeetingScheduleController extends AbstractController
{
    #[Route('/{id}/meetings', name: 'smeetings', methods: ['GET'])]
    public function index(MeetingScheduleRepository $meetingScheduleRepository): Response
    {
        return $this->render('sero/meeting_schedule/index.html.twig', [
            'meeting_schedules' => $meetingScheduleRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'meeting_schedule', methods: ['GET','POST'])]
    public function schedule(Request $request ,MeetingScheduleRepository $meetingScheduleRepository , EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $meetingSchedule = new MeetingSchedule();
        $form = $this->createForm(MeetingScheduleType::class, $meetingSchedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($meetingSchedule);
            $entityManager->flush();

            return $this->redirectToRoute('meeting_schedule', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/meeting_schedule/calendar.html.twig', [
            'meeting_schedules' => $meetingScheduleRepository->findAll(),
            'form' => $form,
            // 'trainings' => $meetingScheduleRepository->findAll(),
        ]);
    }


 

    #[Route('/{id}', name: 'meeting_schedule_show', methods: ['GET'])]
    public function show(MeetingSchedule $meetingSchedule): Response
    {
        return $this->render('sero/meeting_schedule/show.html.twig', [
            'meeting_schedule' => $meetingSchedule,
        ]);
    }

    #[Route('/{id}/edit', name: 'meeting_schedule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MeetingSchedule $meetingSchedule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeetingScheduleType::class, $meetingSchedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('meeting_schedule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/meeting_schedule/edit.html.twig', [
            'meeting_schedule' => $meetingSchedule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'meeting_schedule_delete', methods: ['POST'])]
    public function delete(Request $request, MeetingSchedule $meetingSchedule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meetingSchedule->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($meetingSchedule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('meeting_schedule_index', [], Response::HTTP_SEE_OTHER);
    }
}
