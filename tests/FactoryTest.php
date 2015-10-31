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
        $generatedString = $this->factory->generate();

        $this->assertRegExp('/^[a-z0-9\_\-]{7,7}$/i', $generatedString);
    }

    /**
     * @return array[]
     */
    public function alphabetsProvider()
    {
        $alphabets = array();
        $chars = array();

        for ($i = 1; $i <= 65533; ++$i) {
            $chars[] = mb_convert_encoding("&#$i;", 'UTF-8', 'HTML-ENTITIES');
        }
        $chars = preg_replace('/[^\p{Ll}]/u', '', $chars);
        $chars = array_filter(array_map('trim', $chars));

        for ($i = 0; $i < 100; ++$i) {
            shuffle($chars);
            $alphabets[] = array(implode(null, array_slice($chars, 0, 64)));
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
        return array(
            array(null),
            array('test'),
            array(sha1(rand())),
        );
    }

    public function testGetFactory()
    {
        $factory = Factory::getFactory();

        $this->assertInstanceOf('RandomLib\Factory', $factory);
    }
}
