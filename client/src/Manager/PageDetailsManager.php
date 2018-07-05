<?php
namespace App\Manager;

use App\Entity\Constants\Constant;
use Doctrine\ORM\EntityManagerInterface;

class PageDetailsManager extends BaseManager
{
    const SERVICE_NAME = 'app.page_details_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

}