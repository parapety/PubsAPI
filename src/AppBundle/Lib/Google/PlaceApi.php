<?php

namespace AppBundle\Lib\Google;

/**
 * Service for Google Place Api.
 */
class PlaceApi extends AbstractApi implements PlaceApiInterface
{
    /**
     * @var string Google Api url
     */
    protected $apiUrl = 'https://maps.googleapis.com/maps/api/place/{method}/json?key={key}';

    /**
     * {@inheritdoc}
     */
    public function getBarsInRange(array $latlng, $radius)
    {
        $result = $this->get($this->buildRequest(['method' => 'radarsearch', 'location' => join(',', $latlng), 'type' => 'bar', 'radius' => $radius]));
        if (!empty($result['data'])) {
            $data = [];
            foreach ($result['data'] as $item) {
                $data[] = ['location' => $item['geometry']['location'], 'place_id' => $item['place_id']];
            }
            $result['data'] = $data;
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getDetails($placeId)
    {
        $result = $this->get($this->buildRequest(['method' => 'details', 'placeid' => $placeId]));
        if (!empty($result['data'])) {
            $result['data'] = [
                'location' => $result['data']['geometry']['location'],
                'place_id' => $result['data']['place_id'],
                'address' => $result['data']['formatted_address'],
                'phone' => isset($result['data']['formatted_phone_number']) ? $result['data']['formatted_phone_number'] : '',
                'name' => $result['data']['name']
            ];
        }
        return $result;
    }
}
