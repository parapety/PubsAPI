<?php

namespace AppBundle\Tests\Lib\Google;

use AppBundle\Lib\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testPrepareUrl()
    {
        $this->assertEquals(
            'https://maps.googleapis.com/maps/api/place/radarsearch/json?location=0.00,1.00&radius=100&key=ApiKey&extra=param',
            Helper::prepareUrl('https://maps.googleapis.com/maps/api/place/{method}/json?location={location}&radius={radius}&key={key}', [
                    'METHOD' => 'radarsearch',
                    'RADIUS' => '100',
                    'key' => 'ApiKey',
                    'location' => '0.00,1.00',
                    'extra' => 'param',
                ]
            )
        );
    }

    public function testLocationToArray()
    {
        $this->assertEquals(['lat' => 10.11, 'lng' => 22.13], Helper::locationToArray('10.11,22.13'));
    }

    public function testEmptyLocationToArray()
    {
        $this->assertEquals(['lat' => 54.348545, 'lng' => 18.6532295], Helper::locationToArray(''));
    }
}
