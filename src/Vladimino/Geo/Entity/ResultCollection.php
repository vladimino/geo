<?php

namespace Vladimino\Geo\Entity;

/**
 * Result
 * Represents a single object in the result.
 *
 * @package Vladimino\Geo\Entity
 * @author vladimino
 */
class ResultCollection implements \Iterator
{
    /**
     * Count of objects received from the
     * given location
     *
     * @var int
     */
    public $iCount = 0;

    /**
     * Current position
     *
     * @var int
     */
    private $iPosition = 0;

    /**
     * Given Location
     *
     * @var string
     */
    public $sLocation;


    /**
     * Unsorted list of objects received from the
     * actual search query.
     *
     * @var Result[]
     */
    public $aResults = array();

    /**
     * @param string $sLocation
     * @param array $aResults
     */
    function __construct($sLocation, array $aResults)
    {
        $this->sLocation = $sLocation;
        $this->aResults = $aResults;
        $this->iCount = count($aResults);
        $this->iPosition = 0;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->aResults[$this->iPosition];
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        ++$this->iPosition;
    }

    /**
     * Return the key of the current element
     * @return int
     */
    public function key()
    {
        return $this->iPosition;
    }

    /**
     * Checks if current position is valid
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->aResults[$this->iPosition]);
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        $this->iPosition = 0;
    }


}