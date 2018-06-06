<?php
namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class BaseManager
 *
 * @package App\Manager
 */
abstract class BaseManager
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     *
     * @var type
     */
    protected $class;

    /**
     *
     * @param EntityManagerInterface $entityManager
     * @param type                   $class
     */
    public function __construct(EntityManagerInterface $entityManager, $class)
    {
        $this->entityManager = $entityManager;
        $this->class = $class;
        $this->repository = $this->entityManager->getRepository($this->class);
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function save($entity)
    {
        $this->entityManager->persist($entity);

        return $entity;
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function saveAndFlush($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
        $this->flushAndClear();

        return true;
    }

    /**
     * @return mixed
     */
    public function flushAndClear()
    {
        $this->entityManager->flush();
    }

    /**
     * @return mixed
     */
    public function createNew()
    {
        $class = $this->class;

        return new $class();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param integer $id
     *
     * @return object
     */
    public function find($id)
    {
        return  $this->repository->findOneBy(['id' => $id]);
    }

    /**
     * @param array      $_criteria
     * @param array|null $_orderBy
     * @param null       $_limit
     * @param null       $_offset
     *
     * @return array
     */
    public function findBy(array $_criteria, array $_orderBy = null, $_limit = null, $_offset = null)
    {
        return $this->repository->findBy($_criteria, $_orderBy, $_limit, $_offset);
    }

    /**
     * @param array $criteria
     *
     * @return object
     */
    public function findOneBy(array $criteria)
    {
        return  $this->repository->findOneBy($criteria);
    }
}
