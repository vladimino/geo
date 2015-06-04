<?php

// One of the top reasons for me to use Composer, is this autoload magic
require __DIR__ . "/../../../../vendor/autoload.php";

use Vladimino\Geo\Client\GeoClient;

/**
 * Instantiate Client Application
 *
 * @var \Vladimino\Geo\Client\GeoClient
 */
$oClient = new GeoClient();

// Execute given via options command whenever you wish
$oClient->executeCommand();


