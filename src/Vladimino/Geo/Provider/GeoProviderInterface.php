<?php

namespace Vladimino\Geo\Provider;

/**
 * The implementation is responsible for resolving the id of the city from the
 * given city name (in this simple case via an array of CityName => id). The second
 * responsibility is to sort the returning result from the partner service in whatever
 * way.
 *
 * @author vladimino
 */

interface GeoProviderInterface
{
    /**
     * @param string $sLocation String to geocode.
     *
     * @return \Vladimino\Geo\Entity\ResultCollection
     * @throws \InvalidArgumentException if string is invalid
     */
    public function getResultsByLocation($sLocation);
} 