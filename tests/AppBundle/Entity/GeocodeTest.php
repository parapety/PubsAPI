<?php
namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Geocode;

class GeocodeTest extends \PHPUnit_Framework_TestCase
{
    public function testLatLng()
    {
        $geocode = new Geocode();
        $geocode->setLng(11.11);
        $geocode->setLat(21.21);
        $this->assertEquals('21.21,11.11', $geocode->getLatLng());
    }

    public function testToArray()
    {
        $geocode = new Geocode();

        $geocode->setLng(11.11);
        $geocode->setLat(21.21);
        $geocode->setAddress('Kraków');

        $this->assertEquals([
            'latlng' => '21.21,11.11',
            'address' => 'Kraków',
        ], $geocode->toArray());
    }
}