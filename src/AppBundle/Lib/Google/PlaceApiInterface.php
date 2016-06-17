<?php

namespace AppBundle\Lib\Google;

/**
 * Place Api Interface to get bars location, and details about place
 */
interface PlaceApiInterface
{
    /**
     * Returns location's nearby bars in rage
     *
     * @param array $latlng
     * @param int $radius in meters
     * @return array[
     *  @var int $status_code http response code
     *  @var string $status i.e. "OK" @link https://developers.google.com/places/web-service/search#PlaceSearchStatusCodes
     *  @var array $data [[
     *      Contains location informations, will be empty if $status other than OK
     *      @var array location [
     *          @var float $lat.
     *          @var float $lng.
     *      ],
     *      @var string $place_id google place id.
     *  ]],
     *  @var array $html_attributions
     *  @var string $error error message if status code other than 200
     * ]
     */
    public function getBarsInRange(array $latlng, $radius);

    /**
     * Returns details about place
     *
     * @param $placeId
     * @return array[
     *  @var int $status_code http response code
     *  @var string $status i.e. "OK" @link https://developers.google.com/places/web-service/search#PlaceSearchStatusCodes
     *  @var array $data [
     *      Contains location informations, will be empty if $status other than OK
     *      @var array location [
     *          @var float $lat.
     *          @var float $lng.
     *      ],
     *      @var string $place_id google place id.
     *      @var string $name
     *      @var string $address
     *  ],
     *  @var array $html_attributions
     *  @var string $error error message if status code other than 200
     * ]
     */
    public function getDetails($placeId);
}
