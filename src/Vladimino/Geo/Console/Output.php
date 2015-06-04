<?php

namespace Vladimino\Geo\Console;

/**
 * Class Output - adapter for console output classes
 *
 * @package Vladimino\Geo\Console
 * @author vladimino
 */
class Output implements OutputInterface
{
    /**
     * Prints given message according to environment
     *
     * @param string $sMessage
     */
    public static function printMessage($sMessage)
    {
        // Dirty check right from PHP documentation
        if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
            WinOutput::printMessage($sMessage);
        } else {
            BashOutput::printMessage($sMessage);
        }
    }


}