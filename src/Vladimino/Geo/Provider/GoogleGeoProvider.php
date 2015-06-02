<?php

namespace Vladimino\Geo\Provider;

use Vladimino\Geo\Entity\Result;
use Vladimino\Geo\Entity\ResultCollection;

/**
 * The implementation is responsible for resolving the id of the city from the
 * given city name (in this simple case via an array of CityName => id). The second
 * responsibility is to sort the returning result from the partner service in whatever
 * way.
 *
 * @author vladimino
 */
class GoogleGeoProvider implements GeoProviderInterface
{

    /**
     * @param string $sLocation String to geocode.
     *
     * @return \Vladimino\Geo\Entity\ResultCollection
     * @throws \InvalidArgumentException if string is invalid
     */
    public function getResultsByLocation($sLocation)
    {
        $res1 = new Result();
        $res1->city = 'city1';

        $res2 = new Result();
        $res2->city = 'city2';

        $collection = new ResultCollection($sLocation, array($res1, $res2));

        return $collection;
    }
} 