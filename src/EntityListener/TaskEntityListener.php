<?php

namespace App\EntityListener;

use App\Entity\Task;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class TaskEntityListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Task $task, LifecycleEventArgs $event)
    {
        $task->setCreatedBy($this->security->getUser());
    }
}