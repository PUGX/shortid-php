<?php

namespace PUGX\Shortid\Test;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use PUGX\Shortid\Factory;
use PUGX\Shortid\Shortid;

final class ShortidTest extends TestCase
{
    protected function tearDown(): void
    {
        Shortid::setFactory(null);
    }

    public function testGenerate(): void
    {
        $generated = Shortid::generate();

        $this->assertRegExp('/^[a-z0-9\_\-]{7}$/i', $generated->__toString());
    }

    public function testGenerateWithReadable(): void
    {
        $generated = Shortid::generate(null, null, true);

        $this->assertRegExp('/^[a-z0-9\_\-]{7}$/i', $generated->__toString());
    }

    public function testGenerateWithLength(): void
    {
        $generated = Shortid::generate(8);

        $this->assertRegExp('/^[a-z0-9\_\-]{8}$/i', $generated->__toString());
    }

    public function testGetFactory(): void
    {
        $factory = Shortid::getFactory();

        $this->assertInstanceOf(Factory::class, $factory);
    }

    public function testSetFactory(): void
    {
        /** @var Factory $factoryMock */
        $factoryMock = $this->createMock(Factory::class);
        Shortid::setFactory($factoryMock);

        $this->assertSame($factoryMock, Shortid::getFactory());
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Shortid::isValid('shortid'));
    }

    public function testIsNotValid(): void
    {
        $this->assertFalse(Shortid::isValid('/(;#!'));
        $this->assertFalse(Shortid::isValid('harmful string stuff'));
    }

    public function testIsValidWithRegexChar(): void
    {
        $factory = Shortid::getFactory();
        $factory->setAlphabet('hìjklmnòpqrstùvwxyzABCDEFGHIJKLMNOPQRSTUVWX.\+*?[^]$(){}=!<>|:-/');
        Shortid::setFactory($factory);

        $this->assertTrue(Shortid::isValid('slsh/]?'));
    }

    public function testJsonSerializable(): void
    {
        $generated = Shortid::generate();

        $this->assertInstanceOf(JsonSerializable::class, $generated);
    }

    public function testJsonEncode(): void
    {
        $generated = Shortid::generate();

        $this->assertSame('"'.$generated.'"', \json_encode($generated));
    }

    public function testSerialize(): void
    {
        $shortid = new Shortid('shortid');

        $this->assertSame('shortid', $shortid->serialize());
    }

    public function testUnserialize(): void
    {
        $shortid = Shortid::generate();
        $shortid->unserialize('shortid');

        $this->assertSame('shortid', (string) $shortid);
    }

    public function testMagicSerialize(): void
    {
        $shortid = new Shortid('shortid');

        $this->assertSame(['id' => 'shortid'], $shortid->__serialize());
    }

    public function testMagicUnserialize(): void
    {
        $shortid = Shortid::generate();
        $shortid->__unserialize(['id' => 'shortid']);

        $this->assertSame('shortid', (string) $shortid);
    }
}
