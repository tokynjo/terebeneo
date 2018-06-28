<?php
namespace App\Manager;

use App\Entity\Constants\Constant;
use Doctrine\ORM\EntityManagerInterface;

class HeaderFooterManager extends BaseManager
{
    const SERVICE_NAME = 'app.header_footer_manager';

    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        parent::__construct($entityManager, $class);
    }

    public function getAllHeaderFooterActive()
    {
        return $this->repository->getAllHeaderFooterActive();
    }

    /**
     * find the active header footer of the partner
     * @param null $partnerId
     * @return null|object
     */
    public function getHeaderFooterActiveByPartner($partnerId = null)
    {
        return $this->repository->findOneBy(['partner' => $partnerId, 'deleted' => Constant::NOT_DELETED]);
    }
}