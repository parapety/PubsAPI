<?php
namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Location;

class LocationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider entitiesProvider
     */
    public function testSetHtmlAttributions(Location $location)
    {
        $this->assertEquals(['<img src="" />'], $location->getHtmlAttributions());
    }

    public function entitiesProvider()
    {
        return [
            [(new Location())->setHtmlAttributions('<img src="" />')],
            [(new Location())->setHtmlAttributions(['<img src="" />'])]
        ];
    }

    /**
     * @dataProvider emptyEntitiesProvider
     */
    public function testSetHtmlAttributionsSetEmpty(Location $location)
    {
        $this->assertEquals([], $location->getHtmlAttributions());
    }

    public function emptyEntitiesProvider()
    {
        return [
            [(new Location())->setHtmlAttributions('')],
            [(new Location())->setHtmlAttributions(null)],
            [(new Location())->setHtmlAttributions([])],
            [(new Location())->setHtmlAttributions(0)]
        ];
    }
}