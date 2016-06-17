<?php

namespace AppBundle\Tests\Lib\Google;

use AppBundle\Lib\Google\PlaceApi;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class PlaceApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function testGetBarsInRange()
    {
        $response = $this->getMockBuilder(Response::class)
            ->setConstructorArgs([200, [], json_encode([
                'status' => 'OK',
                'results' => [
                    [
                        'geometry' => [
                            'location' => ['lat' => '10.25', 'lng' => '11.25'],
                        ],
                        'place_id' => '111',
                        'id' => '7f9359bb5fc02743965ccf02f3662b144ad51547',
                        'reference' => 'QPIh5Ie7H_P3sTkDqeT8A2iu_O4siPZpHFRYbHU8kKt9PA0KsBR_NJDYEsElGBWVtx3t0mZ43QeFrYnpiOtp_q9QD'
                    ], [
                        'geometry' => [
                            'location' => ['lat' => '10.05', 'lng' => '11.05'],
                        ],
                        'place_id' => '222',
                        'id' => '2t9359bb5fc02743965ccf02f3662b144ad51547',
                        'reference' => 'rGnTQ1ve5GgZ7YcF4BccDnI-g5NZ_-sDoqJmsbQvpQrh7jbCBxIQr-rK-YAxkKfH6vu2GlgWxBoUu78Z_0Gpd'
                    ]
                ],
                'html_attributions' => []
            ])])
            ->setMethods(null)
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['send'])
            ->getMock();

        $client->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $placeApi = new PlaceApi($client, 'fakeApiKey');
        $respone = $placeApi->getBarsInRange(['lat' => '10.20', 'lng' => '11.20'], 100);

        $this->assertEquals([
            'status_code' => 200,
            'status' => 'OK',
            'data' => [
                [
                    'location' => [
                        'lat' => 10.25,
                        'lng' => 11.25
                    ],
                    'place_id' => '111'
                ], [
                    'location' => [
                        'lat' => 10.05,
                        'lng' => 11.05,
                    ],
                    'place_id' => '222'
                ]
            ],
            'html_attributions' => []
        ], $respone);
    }

    /**
     * {@inheritdoc}
     */
    public function testGetDetails()
    {
        $response = $this->getMockBuilder(Response::class)
            ->setConstructorArgs([200, [], $this->placeDetailBody()])
            ->setMethods(null)
            ->getMock();

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['send'])
            ->getMock();

        $client->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $placeApi = new PlaceApi($client, 'fakeApiKey');
        $respone = $placeApi->getDetails('111');

        $this->assertEquals([
            'status_code' => 200,
            'status' => 'OK',
            'data' => [
                'location' => [
                    'lat' => 54.44394699999999,
                    'lng' => 18.567788
                ],
                'place_id' => 'ChIJff6B-5EK_UYRSwgUKI06pig',
                'address' => 'Bohaterów Monte Cassino 54B, Sopot, Poland',
                'name' => 'Teatr i Baroteka BOTO',
                'phone' => ''
            ],
            'html_attributions' => []
        ], $respone);
    }

    /**
     * place detail response in json
     * @return string
     */
    protected function placeDetailBody()
    {
        return '{ "html_attributions" : [], "result" : { "address_components" : [ { "long_name" : "54B", "short_name" : "54B", "types" : [ "street_number" ] }, { "long_name" : "Bohaterów Monte Cassino", "short_name" : "Bohaterów Monte Cassino", "types" : [ "route" ] }, { "long_name" : "Dolny Sopot", "short_name" : "Dolny Sopot", "types" : [ "sublocality_level_1", "sublocality", "political" ] }, { "long_name" : "Sopot", "short_name" : "Sopot", "types" : [ "locality", "political" ] }, { "long_name" : "Sopot", "short_name" : "Sopot", "types" : [ "administrative_area_level_2", "political" ] }, { "long_name" : "pomorskie", "short_name" : "pomorskie", "types" : [ "administrative_area_level_1", "political" ] }, { "long_name" : "Poland", "short_name" : "PL", "types" : [ "country", "political" ] } ], "adr_address" : "\u003cspan class=\"street-address\"\u003eBohaterów Monte Cassino 54B\u003c/span\u003e, \u003cspan class=\"locality\"\u003eSopot\u003c/span\u003e, \u003cspan class=\"country-name\"\u003ePoland\u003c/span\u003e", "formatted_address" : "Bohaterów Monte Cassino 54B, Sopot, Poland", "geometry" : { "location" : { "lat" : 54.44394699999999, "lng" : 18.567788 } }, "icon" : "https://maps.gstatic.com/mapfiles/place_api/icons/bar-71.png", "id" : "51886794ab8564fc0d435ee550d971ed4f8404a0", "name" : "Teatr i Baroteka BOTO", "opening_hours" : { "open_now" : true, "periods" : [ { "close" : { "day" : 0, "time" : "2330" }, "open" : { "day" : 0, "time" : "1000" } }, { "close" : { "day" : 1, "time" : "2330" }, "open" : { "day" : 1, "time" : "1000" } }, { "close" : { "day" : 2, "time" : "2330" }, "open" : { "day" : 2, "time" : "1000" } }, { "close" : { "day" : 3, "time" : "2330" }, "open" : { "day" : 3, "time" : "1000" } }, { "close" : { "day" : 4, "time" : "2330" }, "open" : { "day" : 4, "time" : "1000" } }, { "close" : { "day" : 5, "time" : "2330" }, "open" : { "day" : 5, "time" : "1000" } }, { "close" : { "day" : 6, "time" : "2330" }, "open" : { "day" : 6, "time" : "1000" } } ], "weekday_text" : [ "Monday: 10:00 AM â 11:30 PM", "Tuesday: 10:00 AM â 11:30 PM", "Wednesday: 10:00 AM â 11:30 PM", "Thursday: 10:00 AM â 11:30 PM", "Friday: 10:00 AM â 11:30 PM", "Saturday: 10:00 AM â 11:30 PM", "Sunday: 10:00 AM â 11:30 PM" ] }, "place_id" : "ChIJff6B-5EK_UYRSwgUKI06pig", "reference" : "CnRoAAAADmCM591vSoj4200PGjRqpQRGP2iY7nTEDHb70gKW7yvGvNgRs2LYZqYARmzZ8_Rtb5ihutYkB-aozlHRVg84id_j_6CNp_dBJf3ojmiu_M4Ngc3cgguRRcS8OWG7evpCDr2ZyX5INECEJHTBj25h7xIQz4e_jIopHb6jXb_hR_6bLRoUC_YLnb9JmeDYYZsU1xJVj5b4qBk", "scope" : "GOOGLE", "types" : [ "bar", "point_of_interest", "establishment" ], "url" : "https://maps.google.com/?cid=2929092985588287563", "utc_offset" : 120, "vicinity" : "Bohaterów Monte Cassino 54B, Sopot", "website" : "http://www.boto.art.pl/" }, "status" : "OK" }';
    }
}
