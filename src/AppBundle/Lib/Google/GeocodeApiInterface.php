<?php

namespace AppBundle\Lib\Google;

/**
 * Geocode Api Interface to get info about address
 */
interface GeocodeApiInterface
{
    /**
     * Returns geolocation, formatted address and place ID for given address
     *
     * @param string $address
     * @return array[
     *  @var int $status_code http response code
     *  @var string $status i.e. "OK" @link https://developers.google.com/places/web-service/search#PlaceSearchStatusCodes
     *  @var array $data [
     *      [
     *          Contains location informations, will be empty if status other than OK
     *          @var array location [
     *              @var float $lat.
     *              @var float $lng.
     *          ],
     *          @var string $address formatted address.
     *          @var string $place_id google place id.
     *      ]
     *  ],
     *  @var array $html_attributions
     *  @var string $error error message if status code other than 200
     * ]
     */
    public function getLocation($address);
}
