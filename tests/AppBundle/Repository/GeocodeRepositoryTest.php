<?php

namespace AppBundle\Tests\Repository;

use AppBundle\Repository\GeocodeRepository;

class GeocodeRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFindByAddress()
    {
        $repository = $this->getMockBuilder(GeocodeRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $repository->findByAddress(null);
    }
}
