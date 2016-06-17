<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="location", indexes={@ORM\Index(name="latlng", columns={"lat", "lng"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LocationRepository")
 */
class Location
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
     * @ORM\ManyToMany(targetEntity="Place")
     */
    private $places;

    public function __construct()
    {
        $this->places = new ArrayCollection();
    }

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
     * @return Location
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
     * @return Location
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
     * Add place
     *
     * @param \AppBundle\Entity\Place $place
     *
     * @return Location
     */
    public function addPlace(\AppBundle\Entity\Place $place)
    {
        $this->places[] = $place;

        return $this;
    }

    /**
     * Remove place
     *
     * @param \AppBundle\Entity\Place $place
     */
    public function removePlace(\AppBundle\Entity\Place $place)
    {
        $this->places->removeElement($place);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaces()
    {
        return $this->places;
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
