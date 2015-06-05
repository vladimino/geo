<?php

namespace Vladimino\Geo\Provider;

class GeoProviderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetProviderValidName()
    {
        /** @var \Vladimino\Geo\Provider\GeoProviderInterface $oGeoProvider */
        $oGeoProvider = GeoProviderFactory::getProvider("google");
        $this->assertInternalType('object', $oGeoProvider);
        $this->assertTrue($oGeoProvider instanceof GeoProviderInterface);
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testGetProviderInvalidName()
    {
        /** @var \Vladimino\Geo\Provider\GeoProviderInterface $oGeoProvider */
        $oGeoProvider = GeoProviderFactory::getProvider("it can't be provider name");
        $this->assertNull($oGeoProvider);
    }

}