<?php

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class EntityLoggingSubscriber implements EventSubscriber
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::prePersist,
            Events::preRemove,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        // Implement logic to log entity update
        $this->logger->info('Entity updated', ['entity' => get_class($entity)]);
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        // Implement logic to log new entity creation
        $this->logger->info('Entity created', ['entity' => get_class($entity)]);
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        // Implement logic to log entity removal
        $this->logger->info('Entity removed', ['entity' => get_class($entity)]);
    }
}
