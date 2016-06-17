<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Place
 *
 * @ORM\Table(name="place")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlaceRepository")
 */
class Place
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=1024)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="place_id", type="string", length=255, unique=true)
     */
    private $placeId;

    /**
     * @var number
     *
     * @ORM\Column(name="lat", type="decimal", precision=10, scale=7)
     */
    private $lat;

    /**
     * @var number
     *
     * @ORM\Column(name="lng", type="decimal", precision=10, scale=7)
     */
    private $lng;

    /**
     * @var string
     *
     * @ORM\Column(name="html_attributions", type="text")
     */
    private $htmlAttributions;

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
     * Set name
     *
     * @param string $name
     *
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Place
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
     * Set placeId
     *
     * @param string $placeId
     *
     * @return Place
     */
    public function setPlaceId($placeId)
    {
        $this->placeId = $placeId;

        return $this;
    }

    /**
     * Get placeId
     *
     * @return string
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * Set lat
     *
     * @param string $lat
     *
     * @return Place
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
     * @return Place
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Place
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'place_id' => $this->getPlaceId(),
            'phone' => $this->getPhone(),
            'lng' => $this->getLng(),
            'lat' => $this->getLat(),
            'address' => $this->getAddress(),
            'html_attributions' => $this->getHtmlAttributions(),
        ];
    }

    /**
     * Set htmlAttributions
     *
     * @param string $htmlAttributions
     *
     * @return Location
     */
    public function setHtmlAttributions($htmlAttributions)
    {
        if (empty($htmlAttributions)) $htmlAttributions = [];
        if (!is_array($htmlAttributions)) $htmlAttributions = [$htmlAttributions];
        $this->htmlAttributions = json_encode($htmlAttributions);

        return $this;
    }

    /**
     * Get htmlAttributions
     *
     * @return string
     */
    public function getHtmlAttributions()
    {
        $res = json_decode($this->htmlAttributions, true);
        if (empty($res)) {
            $res = [];
        }
        return $res;
    }
}
