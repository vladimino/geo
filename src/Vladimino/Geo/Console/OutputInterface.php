<?php

namespace Vladimino\Geo\Console;

/**
 * Interface OutputInterface
 *
 * @package Vladimino\Geo\Console
 * @author vladimino
 */
interface OutputInterface
{
    /**
     * Prints given message
     *
     * @param string $sMessage
     */
    public static function printMessage($sMessage);
} 