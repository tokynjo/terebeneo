<?php
namespace App\Manager;

use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;

class ValidationLogManager extends BaseManager
{
    const SERVICE_NAME = 'app.validation_log_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

}