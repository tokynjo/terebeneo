<?php
namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class HeaderFooterManager extends BaseManager
{
    const SERVICE_NAME = 'app.header_footer_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }
}