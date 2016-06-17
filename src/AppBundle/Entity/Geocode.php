<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Geocode
 *
 * @ORM\Table(name="geocode", indexes={@ORM\Index(name="address", columns={"address"}), @ORM\Index(name="search", columns={"search"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GeocodeRepository")
 */
class Geocode
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="decimal", precision=10, scale=7)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="decimal", precision=10, scale=7)
     */
    private $lng;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=1024)
     */
    private $address;

    /**
     * @var string searched phrase
     *
     * @ORM\Column(name="search", type="string", length=1024)
     */
    private $search;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lat
     *
     * @param string $lat
     *
     * @return Geocode
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     *
     * @return Geocode
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Geocode
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set search
     *
     * @param string $search
     *
     * @return Geocode
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get search
     *
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @return string
     */
    public function getLatLng()
    {
        return $this->lat . ',' . $this->lng;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['latlng' => $this->getLatLng(), 'address' => $this->getAddress()];
    }
}
