<?php

namespace Vladimino\Geo\Client;

use Vladimino\Geo\Provider\GeoProviderFactory;

class GeoClient
{
    /**
     * Version
     */
    const VERSION = '0.1';

    /**
     * @var string
     */
    protected $sProviderName;
    /**
     * @var \Vladimino\Geo\Provider\GeoProviderInterface;
     */
    protected $oProvider;

    /**
     * @var \Vladimino\Geo\Entity\ResultCollection;
     */
    protected $oResultCollection;

    /**
     * @param string $sProviderName
     */
    public function __construct($sProviderName)
    {
        $this->sProviderName = $sProviderName;
    }

    /**
     * @return \Vladimino\Geo\Provider\GeoProviderInterface
     */
    private function getProviderObject()
    {
        try {
            if (is_null($this->oProvider)) {
                $this->oProvider = GeoProviderFactory::getProvider($this->sProviderName);
            }

            return $this->oProvider;

        } catch (\Exception $e) {
            die($e->getMessage());
        }

    }

    /**
     * @param string $sLocation
     *
     * @return \Vladimino\Geo\Entity\ResultCollection
     */
    public function getResultsByLocation($sLocation)
    {
        $this->oResultCollection = $this->getProviderObject()->getResultsByLocation($sLocation);
        return $this->oResultCollection;
    }

    public function printResults()
    {
        printf("Given Location '%s' with provider '%s'.\n", $this->oResultCollection->sLocation, $this->oResultCollection->sProvider);

        if ($this->oResultCollection) {

            printf("Found %d result(s).\n\n", $this->oResultCollection->iCount);

            /** @var \Vladimino\Geo\Entity\Result $oResult */
            foreach ($this->oResultCollection as $oResult) {
                printf("* Result #%d:\n", $this->oResultCollection->iPosition+1);
                printf("\t** Country: %s;\n", $oResult->sCountry);
                printf("\t** State: %s;\n", $oResult->sState);
                printf("\t** City: %s;\n", $oResult->sCity);
                printf("\t** Longitude: %s;\n", $oResult->fLongitude);
                printf("\t** Latitude: %s.\n\n", $oResult->fLatitude);
            }

        } else {
            echo "Unfortunately, no results found\n";
        }
    }


} 