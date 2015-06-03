<?php

namespace Vladimino\Geo\Provider;

use Vladimino\Geo\Entity\Result;
use Vladimino\Geo\Entity\ResultCollection;

/**
 * GeoProviderInterface implementation for Google Geocoding API provider.
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
     * @return \Vladimino\Geo\Entity\ResultCollection|null
     */
    public function getResultsByLocation($sLocation)
    {
        $this->setLocation($sLocation);
        $aResults = $this->makeQuery();

        if(is_null($aResults)){
            return null;
        }

        $res1 = new Result();
        $res1->city = 'city1';

        $res2 = new Result();
        $res2->city = 'city2';

        $collection = new ResultCollection($sLocation, array($res1, $res2));

        return $collection;
    }

    /**
     * @return array|null
     */

    private function makeQuery()
    {
        /**
         * @var string
         */
        $sUrl = $this->buildApiUrl();

        /**
         * @var array
         */
        $aParams = array(
            'address' => $this->getLocation(),
            'key' => $this->getConfig('api_key')
        );

        /**
         * @var string
         */
        $sResult = file_get_contents($sUrl . '?' . http_build_query($aParams));

        /**
         * @var array
         */
        $aResult = json_decode($sResult, true);

        return (isset($aResult['results'])) ? $aResult['results'] : null;
    }

    /**
     * Build URL from config parameters
     *
     * @return string
     */

    private function buildApiUrl()
    {
        return $this->getConfig('url') . $this->getConfig('format');
    }


}