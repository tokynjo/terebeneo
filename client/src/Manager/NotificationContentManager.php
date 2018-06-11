<?php
namespace App\Manager;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class NotificationContentManager extends BaseManager
{
    const SERVICE_NAME = 'app.notification_content_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

}