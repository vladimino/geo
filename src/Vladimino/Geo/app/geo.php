<?php

require __DIR__ . "/../../../../vendor/autoload.php";

use Vladimino\Geo\Client\GeoClient;

/**
 * @var \Vladimino\Geo\Client\GeoClient
 */
$oClient = new GeoClient();
//$oClient = new GeoClient('google');
//$oClient->getResultsByLocation('Moscow, Russia');
$oClient->getResultsByLocation('-#$%%%');
//$oClient->getResultsByLocation('Oranienstraße 164, 10969, Berlin Germany');
$oClient->printResults();


