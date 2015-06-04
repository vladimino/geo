<?php

namespace Vladimino\Geo\Console;

/**
 * Class Console Input responsible to interact with passed options, commands and arguments
 * For this task only options processed
 *
 * @package Vladimino\Geo\Console
 * @author vladimino
 *
 */

class Input
{
    /**
     * Filters passed options
     *
     * @param array $aShortOptions
     * @param array $aLongOptions
     *
     * @return array
     */
    public static function getPassedOptions($aShortOptions, $aLongOptions)
    {
        return getopt(implode($aShortOptions), $aLongOptions);

    }
} 