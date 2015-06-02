<?php

namespace Vladimino\Geo\Client;

use Vladimino\Geo\Provider\GeoProviderFactory;

class GeoClient
{
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
    protected $oResults;

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
            var_dump($e->getMessage());
            die;
        }

    }

    /**
     * @param string $sLocation
     * @return \Vladimino\Geo\Entity\ResultCollection
     */
    public function getResultsByLocation($sLocation)
    {
        $this->oResults = $this->getProviderObject()->getResultsByLocation($sLocation);
        return $this->oResults;
    }


} 