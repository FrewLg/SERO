<?php

namespace App\EventSubscriber;

// use App\Repository\meetingRepository;
use CalendarBundle\CalendarEvents;
use App\Repository\SERO\MeetingScheduleRepository;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private MeetingScheduleRepository $meetingScheduleRepository,
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $router
    )
    {
        
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }
 
    public function onCalendarSetData(CalendarEvent $calendar )
   
    {
         
        $dates=$this->meetingScheduleRepository->findAll();
        foreach ($dates as $meeting) {

            $meetingEvent = new Event(
                $meeting->getName()." - ".count($meeting->getMeetings())." Meetings",
                $meeting->getStartingDate(),
                $meeting->getEndDate()
            );
 
            $meetingEvent->setOptions([
                'backgroundColor' => "#6993FF",
                'borderColor' => "#6993FF",
            ]);
            $meetingEvent->addOption(
                'url',
                $this->router->generate('meetings',['id'=>$meeting->getId()])
            );

            $calendar->addEvent($meetingEvent);
        }
         
    }
}