<?php

namespace AppBundle\Tests\Repository;

use AppBundle\Repository\LocationRepository;

class LocationRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFindOneByLocation()
    {
        $repository = $this->getMockBuilder(LocationRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $repository->findOneByLocation([]);
    }
}
