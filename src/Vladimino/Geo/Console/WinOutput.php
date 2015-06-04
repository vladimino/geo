<?php

namespace Vladimino\Geo\Console;

/**
 * Class WinOutput
 * Prints output in Windows consoles
 *
 * @package Vladimino\Geo\Console
 * @author vladimino
 */
class WinOutput implements OutputInterface
{

    /**
     * Prints given message
     *
     * @param string $sMessage
     */
    public static function printMessage($sMessage)
    {
        // just escape all the color templating and print
        print self::escapeMessage($sMessage);
    }

    /**
     * Escapes templating
     *
     * @param string $sMessage
     *
     * @return string
     */
    protected static function escapeMessage($sMessage)
    {
        /** @var array $aMatches */
        if (preg_match_all("/({.*?})/", $sMessage, $aMatches)) {
            $aMatches = array_unique($aMatches[0]);
            /** @var string $sMatch */
            foreach ($aMatches as $sMatch) {
                $sMessage = str_replace($sMatch, "", $sMessage);
            }
        }
        return $sMessage;
    }

}