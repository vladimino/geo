<?php

namespace Vladimino\Geo\Service;

use Vladimino\Geo\Entity\Result;


/**
 * Class ResultFactory
 * Creates Result object from given components
 *
 * @package Vladimino\Geo\Service
 */
class ResultFactory
{

    /**
     * Creates a new Result Entiry object
     *
     * @param array $components
     * @return Result
     */
    public static function make(array $components)
    {
        return new Result($components[0], $components[1], $components[2], $components[3], $components[4]);
    }
} 