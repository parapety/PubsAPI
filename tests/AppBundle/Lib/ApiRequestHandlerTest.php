<?php

namespace AppBundle\Tests\Lib;

use AppBundle\Entity\Geocode;
use AppBundle\Entity\Location;
use AppBundle\Entity\Place;
use AppBundle\Lib\ApiRequestHandler;
use AppBundle\Lib\Google\GeocodeApi;
use AppBundle\Lib\Google\PlaceApi;
use AppBundle\Repository\GeocodeRepository;
use AppBundle\Repository\LocationRepository;
use AppBundle\Repository\PlaceRepository;

class ApiRequestHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $locationRepositiory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $placeRepositiory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $geocodeRepositiory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $placeApi;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    private $geocodeApi;

    /**
     * @var \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected function setUp()
    {
        $this->locationRepositiory = $this->getMockBuilder(LocationRepository::class)->disableOriginalConstructor();
        $this->placeRepositiory = $this->getMockBuilder(PlaceRepository::class)->disableOriginalConstructor();
        $this->geocodeRepositiory = $this->getMockBuilder(GeocodeRepository::class)->disableOriginalConstructor();
        $this->placeApi = $this->getMockBuilder(PlaceApi::class)->disableOriginalConstructor();
        $this->geocodeApi = $this->getMockBuilder(GeocodeApi::class)->disableOriginalConstructor();
    }

    public function testGetBarsFromLocalRepository()
    {
        $locationEntity = $this->getMockBuilder(Location::class)
            ->setMethods(['getPlaces'])
            ->getMock();
        $locationEntity->expects($this->once())
            ->method('getPlaces')
            ->will($this->returnValue($this->placeEntitiesCollection()));

        $locationRepositiory = $this->locationRepositiory
            ->setMethods(['findOneByLocation'])
            ->getMock();

        $locationRepositiory->expects($this->once())
            ->method('findOneByLocation')
            ->with($this->equalTo(['lat' => '10.10', 'lng' => '11.11']))
            ->will($this->returnValue($locationEntity));

        $requestHandler = new ApiRequestHandler(
            $locationRepositiory,
            $this->placeRepositiory->getMock(),
            $this->geocodeRepositiory->getMock(),
            $this->placeApi->getMock(),
            $this->geocodeApi->getMock()
        );

        $this->assertGetBars($requestHandler);
    }

    public function testGetBarsFromApi()
    {
        $latlng = ['lat' => '10.10', 'lng' => '11.11'];

        $locationRepositiory = $this->locationRepositiory
            ->setMethods(['findOneByLocation', 'save'])
            ->getMock();

        $locationRepositiory->expects($this->once())
            ->method('findOneByLocation')
            ->with($this->equalTo($latlng))
            ->will($this->returnValue(null));

        $placeRepositiory = $this->placeRepositiory
            ->setMethods(['findByPlaceId', 'save'])
            ->getMock();
        $placeRepositiory->expects($this->exactly(2))
            ->method('findByPlaceId')
            ->withConsecutive(
                [$this->equalTo('111')],
                [$this->equalTo('222')]
            )
            ->will($this->returnValue(null));

        $placeApi = $this->placeApi
            ->setMethods(['getBarsInRange', 'getDetails'])
            ->getMock();

        $placeApi->expects($this->once())
            ->method('getBarsInRange')
            ->with($this->equalTo($latlng), $this->equalTo(2000))
            ->will($this->returnValue($this->barsInRangeResult()));

        $placeDetailsMap = array(
            array('111', $this->placeDetailsCollections()[0]),
            array('222', $this->placeDetailsCollections()[1])
        );
        $placeApi->expects($this->exactly(2))
            ->method('getDetails')
            ->will($this->returnValueMap($placeDetailsMap));

        $requestHandler = new ApiRequestHandler($locationRepositiory, $placeRepositiory, $this->geocodeRepositiory->getMock(), $placeApi, $this->geocodeApi->getMock());

        $this->assertGetBars($requestHandler);
    }

    public function testGetBarsWhenLocationFromApiOnePlaceFromLocalRepositoryOtherOneFromApi()
    {
        $latlng = ['lat' => '10.10', 'lng' => '11.11'];

        $locationRepositiory = $this->locationRepositiory
            ->setMethods(['findOneByLocation', 'save'])
            ->getMock();

        $locationRepositiory->expects($this->once())
            ->method('findOneByLocation')
            ->with($this->equalTo($latlng))
            ->will($this->returnValue(null));

        $placeRepositiory = $this->placeRepositiory
            ->setMethods(['findByPlaceId', 'save'])
            ->getMock();
        $placeRepositiory->expects($this->exactly(2))
            ->method('findByPlaceId')
            ->will($this->returnValueMap([
                ['111', $this->placeEntitiesCollection()[0]],
                ['222', null]
            ]));

        $placeApi = $this->placeApi
            ->setMethods(['getBarsInRange', 'getDetails'])
            ->getMock();

        $placeApi->expects($this->once())
            ->method('getBarsInRange')
            ->with($this->equalTo($latlng), $this->equalTo(2000))
            ->will($this->returnValue($this->barsInRangeResult()));

        $placeApi->expects($this->exactly(1))
            ->method('getDetails')
            ->with($this->equalTo('222'))
            ->will($this->returnValue($this->placeDetailsCollections()[1]));

        $requestHandler = new ApiRequestHandler($locationRepositiory, $placeRepositiory, $this->geocodeRepositiory->getMock(), $placeApi, $this->geocodeApi->getMock());

        $this->assertGetBars($requestHandler);
    }

    private function assertGetBars(ApiRequestHandler $requestHandler)
    {
        $this->assertEquals([
            'html_attributions' => [],
            'data' => [
                ['name' => 'Place1', 'place_id' => 111, 'html_attributions' => [],],
                ['name' => 'Place2', 'place_id' => 222, 'html_attributions' => [],]
            ]
        ], $requestHandler->getBars(['lat' => '10.10', 'lng' => '11.11']));
    }

    public function testGetDetailsFromLocalRepository()
    {
        $placeRepositiory = $this->placeRepositiory
            ->setMethods(['findByPlaceId'])
            ->getMock();
        $placeRepositiory->expects($this->once())
            ->method('findByPlaceId')
            ->with($this->equalTo('111'))
            ->will($this->returnValue($this->placeEntitiesCollection()[0]));

        $requestHandler = new ApiRequestHandler($this->locationRepositiory->getMock(), $placeRepositiory, $this->geocodeRepositiory->getMock(), $this->placeApi->getMock(), $this->geocodeApi->getMock());

        $this->assertGetDetails($requestHandler);
    }

    public function testGetDetailsFromApi()
    {
        $placeRepositiory = $this->placeRepositiory
            ->setMethods(['findByPlaceId', 'save'])
            ->getMock();
        $placeRepositiory->expects($this->once())
            ->method('findByPlaceId')
            ->with($this->equalTo('111'))
            ->will($this->returnValue(null));

        $placeApi = $this->placeApi
            ->setMethods(['getDetails'])
            ->getMock();

        $placeApi->expects($this->once())
            ->method('getDetails')
            ->with($this->equalTo('111'))
            ->will($this->returnValue($this->placeDetailsCollections()[0]));

        $requestHandler = new ApiRequestHandler(
            $this->locationRepositiory->getMock(),
            $placeRepositiory,
            $this->geocodeRepositiory->getMock(),
            $placeApi,
            $this->geocodeApi->getMock()
        );

        $this->assertGetDetails($requestHandler);
    }

    private function assertGetDetails(ApiRequestHandler $requestHandler)
    {
        $this->assertEquals([
            'data' => [
                'lat' => '10.25',
                'lng' => '11.25',
                'name' => 'Place1',
                'phone' => '500600700',
                'place_id' => '111',
                'address' => 'Address1',
                'html_attributions' => [],
            ]
        ], $requestHandler->getDetails('111'));
    }

    public function testGetLocationFromLocalRepository()
    {
        $geoRepositiory = $this->geocodeRepositiory
            ->setMethods(['findByAddress'])
            ->getMock();
        $geoRepositiory->expects($this->once())
            ->method('findByAddress')
            ->with($this->equalTo('warszawa'))
            ->will($this->returnValue($this->geocodeEntitiesCollection()));

        $requestHandler = new ApiRequestHandler(
            $this->locationRepositiory->getMock(),
            $this->placeRepositiory->getMock(),
            $geoRepositiory,
            $this->placeApi->getMock(),
            $this->geocodeApi->getMock()
        );

        $this->assertGetLocation($requestHandler);
    }

    public function testGetLocationFromApi()
    {
        $geoRepositiory = $this->geocodeRepositiory
            ->setMethods(['findByAddress', 'save'])
            ->getMock();
        $geoRepositiory->expects($this->once())
            ->method('findByAddress')
            ->with($this->equalTo('warszawa'))
            ->will($this->returnValue(null));
        $geocodeApi = $this->geocodeApi
            ->setMethods(['getLocation'])
            ->getMock();
        $geocodeApi->expects($this->once())
            ->method('getLocation')
            ->with($this->equalTo('warszawa'))
            ->will($this->returnValue($this->geocodeCollections()));

        $requestHandler = new ApiRequestHandler(
            $this->locationRepositiory->getMock(),
            $this->placeRepositiory->getMock(),
            $geoRepositiory,
            $this->placeApi->getMock(),
            $geocodeApi
        );

        $this->assertGetLocation($requestHandler);
    }

    private function assertGetLocation(ApiRequestHandler $requestHandler)
    {
        $this->assertEquals([
            'data' => [
                ['latlng' => '10.20,11.20', 'address' => 'Warszawa, Mazowieckie'],
                ['latlng' => '10.00,11.00', 'address' => 'Warszawa, Polska']
            ]
        ], $requestHandler->getLocation('warszawa'));
    }

    /**
     * fixtures with place entities
     * @return array
     */
    protected function placeEntitiesCollection()
    {
        $tmpPlace = new Place();
        $tmpPlace->setAddress('Address1');
        $tmpPlace->setLat('10.25');
        $tmpPlace->setLng('11.25');
        $tmpPlace->setName('Place1');
        $tmpPlace->setPhone('500600700');
        $tmpPlace->setPlaceId('111');
        $tmpPlace->setHtmlAttributions([]);

        $tmpPlace2 = new Place();
        $tmpPlace2->setAddress('Address2');
        $tmpPlace2->setLat('10.05');
        $tmpPlace2->setLng('11.05');
        $tmpPlace2->setName('Place2');
        $tmpPlace2->setPhone('12 345 34 35');
        $tmpPlace2->setPlaceId('222');
        $tmpPlace2->setHtmlAttributions([]);
        return [$tmpPlace, $tmpPlace2];
    }

    /**
     * fixtures with place details
     * @return array
     */
    protected function placeDetailsCollections()
    {
        return [
            [
                'status_code' => 200,
                'status' => 'OK',
                'html_attributions' => [],
                'data' => [
                    'location' => ['lat' => '10.25', 'lng' => '11.25'],
                    'name' => 'Place1',
                    'phone' => '500600700',
                    'place_id' => '111',
                    'address' => 'Address1',
                ]
            ], [
                'status_code' => 200,
                'status' => 'OK',
                'html_attributions' => [],
                'data' => [
                    'location' => ['lat' => '10.05', 'lng' => '11.05'],
                    'name' => 'Place2',
                    'phone' => '12 345 34 35',
                    'place_id' => '222',
                    'address' => 'Address2',
                ]
            ]
        ];
    }

    /**
     * fixtures with places locations
     * @return array
     */
    protected function barsInRangeResult()
    {
        return [
            'status_code' => 200,
            'status' => 'OK',
            'html_attributions' => [],
            'data' => [
                [
                    'location' => ['lat' => '10.25', 'lng' => '11.25'],
                    'place_id' => '111',
                ], [
                    'location' => ['lat' => '10.05', 'lng' => '11.05'],
                    'place_id' => '222',
                ]
            ]
        ];
    }

    /**
     * fixtures with geocodes entities
     * @return array
     */
    protected function geocodeEntitiesCollection()
    {
        $tmpGeo = new Geocode();
        $tmpGeo->setAddress('Warszawa, Mazowieckie');
        $tmpGeo->setLat('10.20');
        $tmpGeo->setLng('11.20');
        $tmpGeo->setSearch('warszawa');

        $tmpGeo2 = new Geocode();
        $tmpGeo2->setAddress('Warszawa, Polska');
        $tmpGeo2->setLat('10.00');
        $tmpGeo2->setLng('11.00');
        $tmpGeo2->setSearch('warszawa');
        return [$tmpGeo, $tmpGeo2];
    }

    /**
     * fixtures with geocodes informations
     * @return array
     */
    protected function geocodeCollections()
    {
        return [
            'status_code' => 200,
            'status' => 'OK',
            'html_attributions' => [],
            'data' => [[
                'location' => ['lat' => '10.20', 'lng' => '11.20'],
                'search' => 'warszawa',
                'address' => 'Warszawa, Mazowieckie',
            ], [
                'location' => ['lat' => '10.00', 'lng' => '11.00'],
                'search' => 'warszawa',
                'address' => 'Warszawa, Polska',
            ]]
        ];
    }
}
