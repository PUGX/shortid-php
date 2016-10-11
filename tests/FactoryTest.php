<?php

namespace PUGX\Shortid\Test;

use PHPUnit_Framework_Assert;
use PHPUnit_Framework_TestCase;
use PUGX\Shortid\Factory;

class FactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new Factory();
    }

    public function testGenerate()
    {
        $generated = $this->factory->generate();

        $this->assertRegExp('/^[a-z0-9\_\-]{7,7}$/i', $generated->__toString());
    }

    /**
     * @return array[]
     */
    public function alphabetsProvider()
    {
        $alphabets = [];
        $chars = [];

        for ($i = 1; $i <= 65533; ++$i) {
            $chars[] = mb_convert_encoding("&#$i;", 'UTF-8', 'HTML-ENTITIES');
        }
        $chars = preg_replace('/[^\p{Ll}]/u', '', $chars);
        $chars = array_filter(array_map('trim', $chars));

        for ($i = 0; $i < 100; ++$i) {
            shuffle($chars);
            $alphabets[] = [implode(null, array_slice($chars, 0, 64))];
        }

        return $alphabets;
    }

    /**
     * @param string $alphabet
     *
     * @dataProvider alphabetsProvider
     */
    public function testSetAlphabet($alphabet)
    {
        $this->factory->setAlphabet($alphabet);

        $newAlphabet = PHPUnit_Framework_Assert::readAttribute($this->factory, 'alphabet');

        $this->assertSame($alphabet, $newAlphabet);
    }

    /**
     * @param string $alphabet
     *
     * @dataProvider wrongAlphabetsProvider
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongAlphabet($alphabet)
    {
        $this->factory->setAlphabet($alphabet);
    }

    /**
     * @return array[]
     */
    public function wrongAlphabetsProvider()
    {
        return [
            'null' => [null],
            'test' => ['test'],
            'rand' => [sha1(rand())],
        ];
    }

    public function testGetFactory()
    {
        $factory = Factory::getFactory();

        $this->assertInstanceOf('RandomLib\Factory', $factory);
    }

    public function testSetLength()
    {
        $this->factory->setLength(5);
        $this->assertSame(5, $this->factory->getLength());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongLengthType()
    {
        $this->factory->setLength('invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrongLengthRange()
    {
        $this->factory->setLength(0);
    }
}
