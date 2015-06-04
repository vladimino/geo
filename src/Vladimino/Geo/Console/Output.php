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
     * Prints given message
     *
     * @param string $sMessage
     */
    public static function printMessage($sMessage)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            WinOutput::printMessage($sMessage);
        } else {
            BashOutput::printMessage($sMessage);
        }
    }


}