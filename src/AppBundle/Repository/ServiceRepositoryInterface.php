<?php

namespace AppBundle\Repository;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * ServiceRepositoryInterface
 *
 * Every Repositiory defined as service shud implement this interface
 */
interface ServiceRepositoryInterface extends ObjectRepository
{
    /**
     * Save entity
     *
     * @param $entity
     * @return mixed
     */
    public function save($entity);
}
