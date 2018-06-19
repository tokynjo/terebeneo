<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class PartnerManager extends BaseManager
{
    const SERVICE_NAME = 'app.partner_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

}
