<?php

use Vladimino\Geo\Console\Output;

class OutputTest extends \PHPUnit_Framework_TestCase
{

    public function testPrintMessage()
    {
        $this->expectOutputString('foo');
        Output::printMessage("foo");
    }
}
