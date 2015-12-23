<?php

namespace PUGX\Shortid\Test;

use PHPUnit_Framework_TestCase;
use PUGX\Shortid\Shortid;

class ShortidTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Shortid::setFactory(null);
    }

    public function testGenerate()
    {
        $generatedString = Shortid::generate();

        $this->assertRegExp('/^[a-z0-9\_\-]{7,7}$/i', $generatedString);
    }

    public function testGetFactory()
    {
        $factory = Shortid::getFactory();

        $this->assertInstanceOf('PUGX\Shortid\Factory', $factory);
    }

    public function testSetFactory()
    {
        $factoryMock = $this->getMock('PUGX\Shortid\Factory');
        Shortid::setFactory($factoryMock);

        $this->assertSame($factoryMock, Shortid::getFactory());
    }

    public function testIsValid()
    {
        $this->assertTrue(Shortid::isValid('shortid'));
    }

    public function testIsNotValid()
    {
        $this->assertFalse(Shortid::isValid('/(;#!'));
    }
}
