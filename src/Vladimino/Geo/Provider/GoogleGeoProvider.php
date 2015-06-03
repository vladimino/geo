<?php

namespace Vladimino\Geo\Provider;

use Vladimino\Geo\Entity\Result;
use Vladimino\Geo\Entity\ResultCollection;
use Vladimino\Geo\Service\ResultFactory;

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
        $aGoogleResults = $this->makeQuery();

        if(is_null($aGoogleResults)){
            return null;
        }

        /** @var \Vladimino\Geo\Entity\ResultCollection $oCollection  */
        $oCollection = new ResultCollection($this->getName(), $this->getLocation());

        /** @var array $aGoogleResult */
        foreach($aGoogleResults as $aGoogleResult)
        {
            /** @var array $aComponents */
            $aComponents = [
                $aGoogleResult['address_components'][5]['long_name'],
                $aGoogleResult['address_components'][4]['long_name'],
                $aGoogleResult['address_components'][3]['long_name'],
                $aGoogleResult['geometry']['location']['lng'],
                $aGoogleResult['geometry']['location']['lat']
            ];

            /** @var \Vladimino\Geo\Entity\Result $oResult */
            $oResult = ResultFactory::make($aComponents);
            $oCollection->addResult($oResult);
        }

        return $oCollection;
    }

    /**
     * @return array|null
     */

    private function makeQuery()
    {
        /** @var string $sUrl */
        $sUrl = $this->buildApiUrl();

        /** @var array $aParams */
        $aParams = array(
            'address' => $this->getLocation(),
            'key' => $this->getConfig('api_key')
        );

        /** @var string $sResponse */
        $sResponse = file_get_contents($sUrl . '?' . http_build_query($aParams));

        /** @var array $aResult */
        $aResult = json_decode($sResponse, true);

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