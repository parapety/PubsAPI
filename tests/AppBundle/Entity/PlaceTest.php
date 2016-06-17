<?php
namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Place;

class PlaceTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $place = new Place();
        $place->setName('Name');
        $place->setPhone('500600700');
        $place->setPlaceId('123');
        $place->setLng(10.10);
        $place->setLat(20.20);
        $place->setAddress('Kraków');
        $place->setHtmlAttributions([]);

        $this->assertEquals([
            'name' => 'Name',
            'place_id' => '123',
            'phone' => '500600700',
            'lng' => 10.10,
            'lat' => 20.20,
            'address' => 'Kraków',
            'html_attributions' => [],
        ], $place->toArray());
    }

    /**
     * @dataProvider entitiesProvider
     */
    public function testSetHtmlAttributions(Place $place)
    {
        $this->assertEquals(['<img src="" />'], $place->getHtmlAttributions());
    }

    public function entitiesProvider()
    {
        return [
            [(new Place())->setHtmlAttributions(['<img src="" />'])],
            [(new Place())->setHtmlAttributions('<img src="" />')]
        ];
    }

    /**
     * @dataProvider emptyEntitiesProvider
     */
    public function testSetHtmlAttributionsSetEmpty(Place $place)
    {
        $this->assertEquals([], $place->getHtmlAttributions());
    }

    public function emptyEntitiesProvider()
    {
        return [
            [(new Place())->setHtmlAttributions('')],
            [(new Place())->setHtmlAttributions(null)],
            [(new Place())->setHtmlAttributions([])],
            [(new Place())->setHtmlAttributions(0)]
        ];
    }

}