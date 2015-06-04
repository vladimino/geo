<?php

namespace Vladimino\Geo\Provider;

/**
 * Interface GeoProviderInterface
 * Common Interface for all GeoProviders to be used in GeoProviderFactory
 *
 * @package Vladimino\Geo\Provider
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