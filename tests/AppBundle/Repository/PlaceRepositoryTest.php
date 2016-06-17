<?php

namespace AppBundle\Tests\Repository;

use AppBundle\Repository\PlaceRepository;

class PlaceRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFindByPlaceId()
    {
        $repository = $this->getMockBuilder(PlaceRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $repository->findByPlaceId(null);
    }
}
