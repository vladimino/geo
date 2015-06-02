<?php

require __DIR__ . "/../../../SplClassLoader.php";

/**
 * @var \SplClassLoader
 */
$oClassLoader = new \SplClassLoader();
$oClassLoader->register();

use Vladimino\Geo\Client\GeoClient;

/**
 * @var \Vladimino\Geo\Client\GeoClient
 */
$oClient = new GeoClient('google2');

/**
 * @var \Vladimino\Geo\Entity\ResultCollection $results
 */
$results = $oClient->getResultsByLocation('Oranienstraße 164, 10969, Berlin Germany');

if ($results) {

    /**
     * @var \Vladimino\Geo\Entity\Result $result
     */
    foreach ($results as $result) {
        echo $result->city . "\n";
    }

} else {
    echo "Results not found\n";
}