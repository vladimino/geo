<?php

require __DIR__ . "/../../../../vendor/autoload.php";

use Vladimino\Geo\Client\GeoClient;

/**
 * @var \Vladimino\Geo\Client\GeoClient
 */
$oClient = new GeoClient();
$oClient->executeCommand();
//$oClient->getResultsByLocation('Oranienstraße 164, 10969, Berlin Germany');


