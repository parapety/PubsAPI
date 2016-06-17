<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testApiIsSuccessful($url)
    {
        $client = static::createClient();

        $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('OK', $response['status']);
    }

    public function urlProvider()
    {
        return [
            ['/api/pubs'],
            ['/api/pub/ChIJndo214UK_UYRf0tLf0ynkxg/detail'],
            ['/api/search?address=warszawa'],
            ['/api/pubs?location=54.4,18.4'],
        ];
    }
}
