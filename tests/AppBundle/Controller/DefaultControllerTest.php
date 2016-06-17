<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Pubs API', $crawler->filter('header h3')->text());

    }

    public function testIndexGoToNeptunsPub()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $placeLink = $crawler->filter('div.neptuns-places ul li a');

        $crawler = $client->click($placeLink->link());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($placeLink->text(), $crawler->filter('div.container h2')->text());
    }

    public function testIndexSearch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $searchForm = $crawler->selectButton('search')->form([
            'form' => [
                'search' => 'Grota-Roweckiego Kraków'
            ]
        ]);
        $crawler = $client->submit($searchForm);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Pubs nearby Grota-Roweckiego, Kraków, Poland', $crawler->filter('div.search-results h4')->text());
    }

    public function testIndexSearchResult()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?form[search]=Grota-Roweckiego+Kraków&submit=search');
        $searchResultLink = $crawler->filter('div.search-results ul li a')->first();

        $crawler = $client->click($searchResultLink->link());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($searchResultLink->text(), $crawler->filter('div.container h2')->text());
    }

    public function testIndexSugestedSearch()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/?form[search]=hotel+hillton&submit=search');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Suggestion for search...', $crawler->filter('div.suggested-search h4')->text());
    }

    public function testPlace()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pub/ChIJOXWghZ9z_UYRgp8hyck5grI');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Club 80\'s', $crawler->filter('div.container h2')->text());
    }

    public function testLocation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pubs/location?location=50.0270778,19.909610800000003');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('50.0270778,19.909610800000003', $crawler->filter('img')->attr('src'));
    }
}