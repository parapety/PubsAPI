<?php

namespace AppBundle\Tests\Lib\Google;

use AppBundle\Lib\Google\GeocodeApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class GeocodeApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function testGetLocation()
    {
        $response = $this->getMockBuilder(Response::class)
            ->setConstructorArgs([200, [], $this->geocodeBody()])
            ->setMethods(null)
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['send'])
            ->getMock();

        $client->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $geoApi = new GeocodeApi($client, 'fakeApiKey');
        $respone = $geoApi->getLocation('hotel hilton');

        $this->assertEquals([
            'status_code' => 200,
            'status' => 'OK',
            'data' => [
                [
                    'location' => [
                        'lat' => -29.5432859,
                        'lng' => 30.2992355
                    ],
                    'place_id' => 'ChIJTxae7gql9h4R_Ju9HO-cBG4',
                    'address' => 'Protea Hotel Hilton, 1 Hilton Ave, Pietermaritzburg, 3245, Republika Południowej Afryki',
                ], [
                    'location' => [
                        'lat' => 53.8071656,
                        'lng' => -3.0539579
                    ],
                    'place_id' => 'ChIJFbHhtnJEe0gR7g7ok7kTD0U',
                    'address' => 'Hilton House Hotel, 58 Tyldesley Rd, Blackpool, Blackpool FY1 5DF, Wielka Brytania',
                ],
            ],
            'html_attributions' => []
        ], $respone);
    }

    /**
     * address matched to search pattern in json
     * @return string
     */
    protected function geocodeBody()
    {
        return '{
   "results" : [
      {
         "address_components" : [
            {
               "long_name" : "Protea Hotel Hilton",
               "short_name" : "Protea Hotel Hilton",
               "types" : [ "point_of_interest", "establishment" ]
            },
            {
               "long_name" : "1",
               "short_name" : "1",
               "types" : [ "street_number" ]
            },
            {
               "long_name" : "Hilton Avenue",
               "short_name" : "Hilton Ave",
               "types" : [ "route" ]
            },
            {
               "long_name" : "Leonard",
               "short_name" : "Leonard",
               "types" : [ "sublocality_level_1", "sublocality", "political" ]
            },
            {
               "long_name" : "Pietermaritzburg",
               "short_name" : "PMB",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Howick",
               "short_name" : "Howick",
               "types" : [ "administrative_area_level_3", "political" ]
            },
            {
               "long_name" : "Indlovu DC",
               "short_name" : "Indlovu DC",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "KwaZulu-Natal",
               "short_name" : "KZN",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "Republika Południowej Afryki",
               "short_name" : "ZA",
               "types" : [ "country", "political" ]
            },
            {
               "long_name" : "3245",
               "short_name" : "3245",
               "types" : [ "postal_code" ]
            }
         ],
         "formatted_address" : "Protea Hotel Hilton, 1 Hilton Ave, Pietermaritzburg, 3245, Republika Południowej Afryki",
         "geometry" : {
            "location" : {
               "lat" : -29.5432859,
               "lng" : 30.2992355
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : -29.5419369197085,
                  "lng" : 30.30058448029149
               },
               "southwest" : {
                  "lat" : -29.5446348802915,
                  "lng" : 30.2978865197085
               }
            }
         },
         "partial_match" : true,
         "place_id" : "ChIJTxae7gql9h4R_Ju9HO-cBG4",
         "types" : [ "restaurant", "food", "lodging", "point_of_interest", "establishment" ]
      },
      {
         "address_components" : [
            {
               "long_name" : "Hilton House Hotel",
               "short_name" : "Hilton House Hotel",
               "types" : [ "premise" ]
            },
            {
               "long_name" : "58",
               "short_name" : "58",
               "types" : [ "street_number" ]
            },
            {
               "long_name" : "Tyldesley Road",
               "short_name" : "Tyldesley Rd",
               "types" : [ "route" ]
            },
            {
               "long_name" : "Blackpool",
               "short_name" : "Blackpool",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Blackpool",
               "short_name" : "Blackpool",
               "types" : [ "postal_town" ]
            },
            {
               "long_name" : "Blackpool",
               "short_name" : "Blackpool",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Wielka Brytania",
               "short_name" : "GB",
               "types" : [ "country", "political" ]
            },
            {
               "long_name" : "FY1 5DF",
               "short_name" : "FY1 5DF",
               "types" : [ "postal_code" ]
            }
         ],
         "formatted_address" : "Hilton House Hotel, 58 Tyldesley Rd, Blackpool, Blackpool FY1 5DF, Wielka Brytania",
         "geometry" : {
            "location" : {
               "lat" : 53.8071656,
               "lng" : -3.0539579
            },
            "location_type" : "ROOFTOP",
            "viewport" : {
               "northeast" : {
                  "lat" : 53.80851458029149,
                  "lng" : -3.052608919708498
               },
               "southwest" : {
                  "lat" : 53.8058166197085,
                  "lng" : -3.055306880291502
               }
            }
         },
         "partial_match" : true,
         "place_id" : "ChIJFbHhtnJEe0gR7g7ok7kTD0U",
         "types" : [ "street_address" ]
      }
   ],
   "status" : "OK"
}
';
    }
}
