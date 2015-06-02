<?php

namespace Vladimino\Geo\Entity;

/**
 * Result
 * Represents a single object in the result.
 *
 * @package Vladimino\Geo\Entity
 * @author vladimino
 */
class Result
{
    /**
     * County Name
     *
     * @var string
     */
    public $country;

    /**
     * State Name
     *
     * @var string
     */
    public $state;

    /**
     * City Name
     *
     * @var string
     */
    public $city;

    /**
     * Object Longitude
     *
     * @var float
     */
    public $longitude;

    /**
     * Object Latitude
     *
     * @var float
     */
    public $latitude;
} 