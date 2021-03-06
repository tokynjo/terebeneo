<?php
namespace App\Repository;
use App\Entity\Constants\Constant;

/**
 * HeaderFooterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HeaderFooterRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * get header footer active
     * @return mixed
     */
    public function getAllHeaderFooterActive() {
        $qb = $this->createQueryBuilder("hf")
            ->innerJoin('App:Partner', 'p', 'WITH', 'p.id = hf.partner')
            ->where("hf.deleted = :deleted")
            ->setParameter('deleted', Constant::NO);
        return  $qb->getQuery()->getResult();
    }
}