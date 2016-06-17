<?php

namespace AppBundle\Repository;

/**
 * PlaceRepositoryInterface
 */
interface PlaceRepositoryInterface extends ServiceRepositoryInterface
{
    /**
     * Search stored place
     *
     * @param string $placeId
     * @return \AppBundle\Entity\Place
     * @throw \InvalidArgumentException
     */
    public function findByPlaceId($placeId);
}
