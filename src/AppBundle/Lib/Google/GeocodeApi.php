<?php

namespace AppBundle\Lib\Google;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

/**
 * Service for Google Geocode Api.
 */
class GeocodeApi extends AbstractApi implements GeocodeApiInterface
{
    /**
     * @var string Google Api url
     */
    protected $apiUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address={address}&key={key}';

    /**
     * {@inheritdoc}
     */
    public function getLocation($address)
    {
        $result = $this->get($this->buildRequest(['address' => urlencode($address)]));
        if (!empty($result['data'])) {
            $data = [];
            foreach ($result['data'] as $item) {
                $data[] = ['location' => $item['geometry']['location'], 'address' => $item['formatted_address'], 'place_id' => $item['place_id']];
            }
            $result['data'] = $data;
        }
        return $result;
    }
}
