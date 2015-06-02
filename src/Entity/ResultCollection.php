<?php

namespace Vladimino\Geo\Entity;

/**
 * Result
 * Represents a single object in the result.
 *
 * @package Vladimino\Geo\Entity
 * @author vladimino
 */
class ResultCollection
{
    /**
     * Count of objects received from the
     * given location
     *
     * @var int
     */
    public $count = 0;

    /**
     * Given Location
     *
     * @var string
     */
    public $location;


    /**
     * Unsorted list of objects received from the
     * actual search query.
     *
     * @var Result[]
     */
    public $results = array();
} 