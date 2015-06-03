<?php

require __DIR__ . "/../../../../vendor/autoload.php";
require __DIR__ . "/../../../SplClassLoader.php";

/**
 * @var \SplClassLoader
 */
$oClassLoader = new \SplClassLoader();
$oClassLoader->register();