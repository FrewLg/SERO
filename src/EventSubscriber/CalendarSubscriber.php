<?php

namespace App\EventSubscriber;

use App\Repository\TrainingRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private TrainingRepository $trainingRepository,
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $router
    )
    {}

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    
    

    public function onCalendarSetData(CalendarEvent $calendar )
   
    {
         
        $dates=$this->trainingRepository->findAll();
        foreach ($dates as $booking) {

            $bookingEvent = new Event(
                $booking->getTrainingRequest()->getTrainingTopic()->getName(),
                $booking->getStartingDate(),
                $booking->getEndDate()
            );
 
            $bookingEvent->setOptions([
                'backgroundColor' => "#6993FF",
                'borderColor' => "#6993FF",
            ]);
            $bookingEvent->addOption(
                'url',
                $this->router->generate('app_training_index')
            );

            $calendar->addEvent($bookingEvent);
        }
         
    }
}