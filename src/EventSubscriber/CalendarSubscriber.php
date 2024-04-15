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
         
        $dates=$this->trainingRepository->findAll();
        foreach ($dates as $training) {

            $trainingEvent = new Event(
                $training->getTrainingRequest()->getTrainingTopic()->getName()." at ".$training->getVenue()->getName(),
                $training->getStartingDate(),
                $training->getEndDate()
            );
 
            $trainingEvent->setOptions([
                'backgroundColor' => "#6993FF",
                'borderColor' => "#6993FF",
            ]);
            $trainingEvent->addOption(
                'url',
                $this->router->generate('app_training_show',['id'=>$training->getId()])
            );

            $calendar->addEvent($trainingEvent);
        }
         
    }
}