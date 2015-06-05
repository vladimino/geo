<?php

namespace Vladimino\Geo\Provider;

use Vladimino\Geo\Entity\Result;
use Vladimino\Geo\Entity\ResultCollection;

class GoogleGeoProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Vladimino\Geo\Provider\GoogleGeoProvider
     */
    protected $oProvider;

    protected function setUp()
    {
        $this->oProvider = new GoogleGeoProvider();
    }

    public function testGetName()
    {
        $this->assertEquals("google", $this->oProvider->getName());
    }

    public function testGetConfigSection()
    {
        $this->assertEquals("geocoding", $this->oProvider->getConfigSection());
    }

    public function testExtractAddressValidAddress()
    {
        /** @var array $aGivenAddressComponents */
        $aGivenAddressComponents = [
            0 => [
                'types' => ['country'],
                'long_name' => 'CountryName'
            ],
            1 => [
                'types' => ['administrative_area_level_1'],
                'long_name' => 'StateName'
            ],
            2 => [
                'types' => ['locality'],
                'long_name' => 'CityName'
            ]
        ];

        /** @var array $aExpectedAddressComponents */
        $aExpectedAddressComponents = [
            'country' => 'CountryName',
            'state' => 'StateName',
            'city' => 'CityName'
        ];

        $this->assertEquals($aExpectedAddressComponents, $this->oProvider->extractAddress($aGivenAddressComponents));
    }

    public function testExtractAddressInvalidAddress()
    {
        /** @var array $aGivenAddressComponents */
        $aGivenAddressComponents = [
            0 => [
                'types' => []
            ]
        ];

        /** @var array $aExpectedAddressComponents */
        $aExpectedAddressComponents = [
            'country' => null,
            'state' => null,
            'city' => null
        ];

        $this->assertEquals($aExpectedAddressComponents, $this->oProvider->extractAddress($aGivenAddressComponents));
    }

    public function testConvertAddressComponentsValidAddress()
    {
        /** @var array $aGivenGoogleAddress */
        $aGivenGoogleAddress = [
            'address_components' => [
                0 => [
                    'types' => ['country'],
                    'long_name' => 'CountryName'
                ],
                1 => [
                    'types' => ['administrative_area_level_1'],
                    'long_name' => 'StateName'
                ],
                2 => [
                    'types' => ['locality'],
                    'long_name' => 'CityName'
                ]
            ],
            'geometry' => [
                'location' => [
                    'lng' => 11.111111,
                    'lat' => 22.222222
                ],

            ]
        ];

        /** @var \Vladimino\Geo\Entity\Result $oResult */
        $oResult = $this->oProvider->convertResult($aGivenGoogleAddress);
        $this->assertInternalType('object', $oResult);
        $this->assertTrue($oResult instanceof Result);

        $this->assertObjectHasAttribute('sCountry', $oResult);
        $this->assertInternalType('string', $oResult->sCountry);
        $this->assertEquals('CountryName', $oResult->sCountry);

        $this->assertObjectHasAttribute('sState', $oResult);
        $this->assertInternalType('string', $oResult->sState);
        $this->assertEquals('StateName', $oResult->sState);

        $this->assertObjectHasAttribute('sCity', $oResult);
        $this->assertInternalType('string', $oResult->sCity);
        $this->assertEquals('CityName', $oResult->sCity);

        $this->assertObjectHasAttribute('fLongitude', $oResult);
        $this->assertInternalType('float', $oResult->fLongitude);
        $this->assertEquals(11.111111, $oResult->fLongitude);

        $this->assertObjectHasAttribute('fLatitude', $oResult);
        $this->assertInternalType('float', $oResult->fLatitude);
        $this->assertEquals(22.222222, $oResult->fLatitude);
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testConvertAddressComponentsInvalidAddress()
    {
        /** @var array $aGivenGoogleAddress */
        $aGivenGoogleAddress = [];
        $this->oProvider->convertResult($aGivenGoogleAddress);
    }

    /**
     * @expectedException     \InvalidArgumentException
     */
    public function testConvertAddressComponentsPartlyInvalidAddress()
    {
        /** @var array $aGivenGoogleAddress */
        $aGivenGoogleAddress = [
            'address_components' => [
                0 => [
                    'types' => ['country'],
                    'long_name' => 'CountryName'
                ],
                1 => [
                    'types' => ['administrative_area_level_1'],
                    'long_name' => 'StateName'
                ],
                2 => [
                    'types' => ['locality'],
                    'long_name' => 'CityName'
                ]
            ],
        ];
        $this->oProvider->convertResult($aGivenGoogleAddress);
    }

    /**
     * @expectedException     \RuntimeException
     */
    public function testConvertAddressComponentsEmptyAddress()
    {
        /** @var array $aGivenGoogleAddress */
        $aGivenGoogleAddress = [
            'address_components' => [
                0 => [
                    'types' => [],
                ],
            ],
            'geometry' => [
                'location' => [
                    'lng' => 11.111111,
                    'lat' => 22.222222
                ],
            ]
        ];

        $this->oProvider->convertResult($aGivenGoogleAddress);
    }

    public function testGetResultsByLocation()
    {
        /**
         * Should be geocoded to the following structure:
         *
         * Country: Germany
         * State: Berlin
         * City: Berlin
         * Longitude: 13.4148426
         * Latitude: 52.5024629
         *
         * @var string $sLocation
         */
        $sLocation = "Oranienstraße 164, 10969, Berlin Germany";

        /** @var \Vladimino\Geo\Entity\ResultCollection $oResultCollection */
        $oResultCollection = $this->oProvider->getResultsByLocation($sLocation);
        $this->assertInternalType('object', $oResultCollection);
        $this->assertTrue($oResultCollection instanceof ResultCollection);

        $this->assertObjectHasAttribute('iCount', $oResultCollection);
        $this->assertInternalType('int', $oResultCollection->iCount);
        $this->assertEquals(1, $oResultCollection->iCount);

        $this->assertObjectHasAttribute('sProvider', $oResultCollection);
        $this->assertInternalType('string', $oResultCollection->sProvider);
        $this->assertEquals('google', $oResultCollection->sProvider);

        $this->assertObjectHasAttribute('sLocation', $oResultCollection);
        $this->assertInternalType('string', $oResultCollection->sLocation);
        $this->assertEquals($sLocation, $oResultCollection->sLocation);

        $this->assertObjectHasAttribute('aResults', $oResultCollection);
        $this->assertInternalType('array', $oResultCollection->aResults);
        $this->assertNotEmpty($oResultCollection->aResults);
        $this->assertEquals(1, count($oResultCollection->aResults));

        /** @var \Vladimino\Geo\Entity\Result $oResult */
        foreach ($oResultCollection->aResults as $oResult) {
            $this->assertInternalType('object', $oResult);
            $this->assertTrue($oResult instanceof Result);

            $this->assertObjectHasAttribute('sCountry', $oResult);
            $this->assertInternalType('string', $oResult->sCountry);
            $this->assertEquals('Germany', $oResult->sCountry);

            $this->assertObjectHasAttribute('sState', $oResult);
            $this->assertInternalType('string', $oResult->sState);
            $this->assertEquals('Berlin', $oResult->sState);

            $this->assertObjectHasAttribute('sCity', $oResult);
            $this->assertInternalType('string', $oResult->sCity);
            $this->assertEquals('Berlin', $oResult->sCity);

            $this->assertObjectHasAttribute('fLongitude', $oResult);
            $this->assertInternalType('float', $oResult->fLongitude);
            $this->assertEquals(13.4148426, $oResult->fLongitude);

            $this->assertObjectHasAttribute('fLatitude', $oResult);
            $this->assertInternalType('float', $oResult->fLatitude);
            $this->assertEquals(52.5024629, $oResult->fLatitude);

        }
    }
}
 