<?php

namespace App\Controller\SERO;

use App\Entity\SERO\Meeting;
use App\Entity\SERO\MeetingSchedule;
use App\Form\SERO\MeetingType;
use App\Repository\SERO\MeetingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// #[Route('/s/e/r/o/meeting')]
#[Route('{_locale<%app.supported_locales%>}/meeting')]

class MeetingController extends AbstractController
{
    #[Route('/{id}', name: 'meetings', methods: ['GET'])]
    public function index(MeetingRepository $meetingRepository,
     MeetingSchedule $meetingSchedule): Response
    {
        return $this->render('sero/meeting/index.html.twig', [
            'meetings' => $meetingRepository->findBy(['meetingSchedule'=>$meetingSchedule]),
        ]);
    }

    #[Route('/{id}/new', name: 'meeting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MeetingSchedule $meetingSchedule,
    EntityManagerInterface $entityManager): Response
    {
        $meeting = new Meeting();
        $form = $this->createForm(MeetingType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meeting->setMeetingSchedule($meetingSchedule);
            $meeting->setMinuteTakenBy($this->getUser());
            $meeting->setCreatedBy($this->getUser());
            $meeting->setStatus(1);
            foreach($meeting->getApplications() as $vals){
                $meeting->addApplication($vals);

            }
            
            $meeting->setMinuteTakenAt(new \DateTime());
            $entityManager->persist($meeting);
            $entityManager->flush();
            $this->addFlash("success", "New meeting has been added succesfully!");

            return $this->redirectToRoute('meetings', ['id'=>$meetingSchedule->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/meeting/new.html.twig', [
            'meeting' => $meeting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/details', name: 'meeting_show', methods: ['GET'])]
    public function show(Meeting $meeting): Response
    {
        return $this->render('sero/meeting/show.html.twig', [
            'meeting' => $meeting,
        ]);
    }

    #[Route('/{id}/edit', name: 'meeting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Meeting $meeting, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeetingType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('meeting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sero/meeting/edit.html.twig', [
            'meeting' => $meeting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'meeting_delete', methods: ['POST'])]
    public function delete(Request $request, Meeting $meeting, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$meeting->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($meeting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('meeting_index', [], Response::HTTP_SEE_OTHER);
    }
}
