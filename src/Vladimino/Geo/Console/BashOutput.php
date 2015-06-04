<?php

namespace Vladimino\Geo\Console;

/**
 * Class BashOutput
 * Prints colorized output in non-Windows consoles
 *
 * @package Vladimino\Geo\Console
 * @author vladimino
 */
class BashOutput implements OutputInterface
{
    /**
     * Color escape codes
     *
     * @var array
     */
    private static $aColors = array(
        'black' => '0;30',
        'dark_gray' => '1;30',
        'red' => '0;31',
        'bold_red' => '1;31',
        'green' => '0;32',
        'bold_green' => '1;32',
        'brown' => '0;33',
        'yellow' => '1;33',
        'blue' => '0;34',
        'bold_blue' => '1;34',
        'purple' => '0;35',
        'bold_purple' => '1;35',
        'cyan' => '0;36',
        'bold_cyan' => '1;36',
        'white' => '1;37',
        'bold_gray' => '0;37',
    );

    /**
     * Prints given message
     *
     * @param string $sMessage
     */
    public static function printMessage($sMessage)
    {
        print self::templateMessage($sMessage);
    }

    /**
     * Escapes templating, cause Windows console does not support color output
     *
     * @param string $sMessage
     *
     * @return string
     */
    protected static function templateMessage($sMessage)
    {
        /** @var array $aMatches */
        if (preg_match_all("/{(.*?)}(.*?){\/.*?}/", $sMessage, $aMatches)) {
            /** @var string $sMatch */
            foreach ($aMatches[0] as $i => $sMatch) {
                $sMessage = str_replace($sMatch, self::setColored($aMatches[2][$i], $aMatches[1][$i]), $sMessage);
            }
        }
        return $sMessage;
    }

    /**
     * Makes string appears in color
     *
     * @param string $sMessage
     * @param string $sColor
     *
     * @return string
     */
    public static function setColored($sMessage, $sColor)
    {
        if (!isset(self::$aColors[$sColor])) {
            return $sMessage;
        }

        return "\033[" . self::$aColors[$sColor] . "m" . $sMessage . "\033[0m";
    }


} 