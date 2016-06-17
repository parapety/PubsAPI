<?php

namespace AppBundle\Repository;

/**
 * LocationRepositoryInterface *
 */
interface LocationRepositoryInterface extends ServiceRepositoryInterface
{
    /**
     * Search stored request for location
     *
     * @param array $latlng [lat => 11.11, lng => 22.22]
     * @return \AppBundle\Entity\Location|null
     * @throw \InvalidArgumentException
     */
    public function findOneByLocation(array $latlng);
}
