<?php
namespace App\Manager;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class NotificationManager extends BaseManager
{
    const SERVICE_NAME = 'app.notification_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

}