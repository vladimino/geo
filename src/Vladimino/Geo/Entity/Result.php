<?php

namespace Vladimino\Geo\Entity;

/**
 * Result
 * Represents a single object in the result.
 *
 * @package Vladimino\Geo\Entity
 * @author vladimino
 */
class Result implements \JsonSerializable
{
    /**
     * County Name
     *
     * @var string
     */
    public $sCountry;

    /**
     * State Name
     *
     * @var string
     */
    public $sState;

    /**
     * City Name
     *
     * @var string
     */
    public $sCity;

    /**
     * Object Longitude
     *
     * @var float
     */
    public $fLongitude;

    /**
     * Object Latitude
     *
     * @var float
     */
    public $fLatitude;

    /**
     * @param string $sCountry
     * @param string $sState
     * @param string $sCity
     * @param float $fLongitude
     * @param float $fLatitude
     */
    public function __construct($sCountry, $sState, $sCity, $fLongitude, $fLatitude)
    {
        $this->sCountry = $sCountry;
        $this->sState = $sState;
        $this->sCity = $sCity;
        $this->fLongitude = $fLongitude;
        $this->fLatitude = $fLatitude;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            "country" => $this->sCountry,
            "state" => $this->sState,
            "city" => $this->sCity,
            "longitude" => $this->fLongitude,
            "latitude" => $this->fLatitude
        ];
    }
} 