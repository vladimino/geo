<?php

namespace Vladimino\Geo\Provider;

use Vladimino\Geo\Entity\Result;
use Vladimino\Geo\Entity\ResultCollection;

/**
 * The implementation for Google Geocoding API provider.
 *
 * @url https://developers.google.com/maps/documentation/geocoding/
 *
 * @author vladimino
 */
class GoogleGeoProvider extends BaseProvider implements GeoProviderInterface
{
    /**
     * Returns the name of the Provider.
     *
     * @return string
     */
    public function getName()
    {
        return 'google';
    }

    /**
     * Returns the name of for the section in configuration file.
     *
     * @return string
     */
    public function getConfigSection()
    {
        return 'geocoding';
    }

    /**
     * @param string $sLocation String to geocode.
     *
     * @return \Vladimino\Geo\Entity\ResultCollection
     * @throws \InvalidArgumentException if string is invalid
     */
    public function getResultsByLocation($sLocation)
    {
        $this->setLocation($sLocation);
        $res1 = new Result();
        $res1->city = 'city1';

        $res2 = new Result();
        $res2->city = 'city2';

        $collection = new ResultCollection($sLocation, array($res1, $res2));

        return $collection;
    }

    /**
     * Location — The street address that you want to geocode, in the format
     * used by the national postal service of the country concerned.
     * Additional address elements such as business names and unit,
     * suite or floor numbers should be avoided.
     * https://maps.googleapis.com/maps/api/geocode/json?address=Oranienstra%C3%9Fe%20164,%2010969,%20Berlin%20Germany&key=AIzaSyB9Ylo5ZLytB0a-wlFnGgeXGK5Y9Ll4p2M
     * @param string $sLocation
     */
    private function makeQuery($sLocation)
    {

    }


}