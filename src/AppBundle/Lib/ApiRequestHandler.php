<?php

namespace AppBundle\Lib;

use AppBundle\Entity\Geocode;
use AppBundle\Entity\Location;
use AppBundle\Entity\Place;
use AppBundle\Lib\Google\GeocodeApiInterface;
use AppBundle\Repository\GeocodeRepositoryInterface;
use AppBundle\Repository\LocationRepositoryInterface;
use AppBundle\Repository\PlaceRepositoryInterface;
use AppBundle\Lib\Google\PlaceApiInterface;

/**
 * Api request handler to combine data from Google API and local storage
 */
class ApiRequestHandler
{
    /**
     * searching range in meters
     */
    const RANGE = 2000;

    /**
     * @var LocationRepositoryInterface
     */
    private $locationRepository;

    /**
     * @var PlaceRepositoryInterface
     */
    private $placeRepository;

    /**
     * @var PlaceApiInterface
     */
    private $placeApi;

    /**
     * @var GeocodeApiInterface
     */
    private $geocodeApi;

    /**
     * @var GeocodeRepositoryInterface
     */
    private $geocodeRepository;

    public function __construct(LocationRepositoryInterface $location, PlaceRepositoryInterface $place, GeocodeRepositoryInterface $geocode, PlaceApiInterface $placeApi, GeocodeApiInterface $geocodeApi)
    {
        $this->locationRepository = $location;
        $this->placeRepository = $place;
        $this->placeApi = $placeApi;
        $this->geocodeApi = $geocodeApi;
        $this->geocodeRepository = $geocode;
    }

    /**
     * Collects informations about bars for given localisation and store request in local database
     *
     * @param array $latlng [lat => 1.111, lng => 2.222]
     * @return array [
     * html_attributions => [],
     * data => [
     *  [@var string $name, @var string $place_id, @var array $html_attributions], ...
     * ]
     * ]
     */
    public function getBars(array $latlng)
    {
        $location = $this->locationRepository->findOneByLocation($latlng);
        if (!$location) {
            $location = new Location();
            $location->setLat($latlng['lat']);
            $location->setLng($latlng['lng']);

            $locations = $this->placeApi->getBarsInRange($latlng, self::RANGE);
            if ($locations['status'] == 'OK' && $c = count($locations['data'])) {
                $location->setHtmlAttributions($locations['html_attributions']);
                for ($i = 0; $i < $c; $i++) {
                    $place = $this->handlePlace($locations['data'][$i]['place_id']);
                    $location->addPlace($place);
                }
                $this->locationRepository->save($location);
            }
        }
        $data = [];
        foreach ($location->getPlaces() as $item) {
            $data[] = ['name' => $item->getName(), 'place_id' => $item->getPlaceId(), 'html_attributions' => $item->getHtmlAttributions()];
        }
        return ['html_attributions' => $location->getHtmlAttributions(), 'data' => $data];
    }

    /**
     * Get details about place and store it in local database
     *
     * @param string $placeId
     * @return array [
     *  'data => [
     *  [@var string $name, @var string $place_id, @var string $lat, @var string $lng, @var string $address, @var string $phone, @var array $html_attributions], ...
     * ]
     * ]
     */
    public function getDetails($placeId)
    {
        return ['data' => $this->handlePlace($placeId)->toArray()];
    }

    /**
     * Get geocodes for given address
     *
     * @param string $searchedAddress
     * @return array [
     * 'data' => [
     *  [@var string $latlng, @var string $address], ...
     * ]
     * ]
     */
    public function getLocation($searchedAddress)
    {
        $geocodes = $this->geocodeRepository->findByAddress($searchedAddress);
        if (!$geocodes) {
            $locationsData = $this->geocodeApi->getLocation($searchedAddress);
            if ($locationsData['status_code'] == 200 && $c = count($locationsData['data'])) {
                for ($i = 0; $i < $c; $i++) {
                    $geocode = new Geocode();
                    $geocode->setLat($locationsData['data'][$i]['location']['lat']);
                    $geocode->setLng($locationsData['data'][$i]['location']['lng']);
                    $geocode->setAddress($locationsData['data'][$i]['address']);
                    $geocode->setSearch($searchedAddress);
                    $geocodes[] = $geocode;
                    $this->geocodeRepository->save($geocode);
                }
            }
        }
        $result = [];
        if (!empty($geocodes)) {
            foreach ($geocodes as $item) {
                $result[] = $item->toArray();
            }
        }
        return ['data' => $result];
    }

    /**
     * checks for pub in local repository if not found, asks API and save result
     *
     * @param $placeId
     * @return Place
     */
    private function handlePlace($placeId)
    {
        $place = $this->placeRepository->findByPlaceId($placeId);
        if (!$place) {
            $placeData = $this->placeApi->getDetails($placeId);
            if ($placeData['status_code'] == 200 && !empty($placeData['data'])) {
                $place = new Place();
                $place->setLat($placeData['data']['location']['lat']);
                $place->setLng($placeData['data']['location']['lng']);
                $place->setAddress($placeData['data']['address']);
                $place->setPhone($placeData['data']['phone']);
                $place->setPlaceId($placeData['data']['place_id']);
                $place->setName($placeData['data']['name']);
                $place->setHtmlAttributions($placeData['html_attributions']);
                $this->placeRepository->save($place);
            }
        }
        return $place;
    }
}
