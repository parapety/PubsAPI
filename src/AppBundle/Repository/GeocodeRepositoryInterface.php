<?php

namespace AppBundle\Repository;

/**
 * GeocodeRepositoryInterface
 */
interface GeocodeRepositoryInterface extends ServiceRepositoryInterface
{
    /**
     * Search stored request for address
     *
     * @param string $address
     * @return \AppBundle\Entity\Geocode[]|null
     * @throw \InvalidArgumentException
     */
    public function findByAddress($address);
}
