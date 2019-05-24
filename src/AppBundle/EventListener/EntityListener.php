<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class EntityListener implements EventSubscriber
{
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getSubscribedEvents()
    {
        return array('prePersist', 'preUpdate');//les événements écoutés
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            $this->logger->info('Utilisateur ajouté.');
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            $this->logger->info('Utilisateur modifié.');
        }
    }
}
