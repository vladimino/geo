<?php

namespace Vladimino\Geo\Client;


use Vladimino\Geo\Provider\GeoProviderFactory;

class GeoClient
{

    protected $sProviderName;
    /**
     * @var \Vladimino\Geo\Provider\GeoProviderInterface;
     */
    protected $oProvider;

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
        if (is_null($this->oProvider)) {
            $this->oProvider = GeoProviderFactory::getProvider($this->sProviderName);
        }

        return $this->oProvider;
    }

    /**
     * @param string $sLocation
     * @return \Vladimino\Geo\Entity\ResultCollection
     */
    public  function getResultsByLocation($sLocation)
    {
        /**
         * @var \Vladimino\Geo\Entity\ResultCollection;
         */
        $oResult = $this->getProviderObject()->getResultsByLocation($sLocation);
        return $oResult;
    }

} 