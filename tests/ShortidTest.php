<?php

namespace PUGX\Shortid\Test;

use JsonSerializable;
use PHPUnit\Framework\TestCase;
use PUGX\Shortid\Factory;
use PUGX\Shortid\InvalidShortidException;
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

        self::assertRegExp('/^[a-z0-9\_\-]{7}$/i', $generated->__toString());
    }

    public function testGenerateWithReadable(): void
    {
        $generated = Shortid::generate(null, null, true);

        self::assertRegExp('/^[a-z0-9\_\-]{7}$/i', $generated->__toString());
    }

    public function testGenerateWithLength(): void
    {
        $generated = Shortid::generate(8);

        self::assertRegExp('/^[a-z0-9\_\-]{8}$/i', $generated->__toString());
    }

    public function testGetFactory(): void
    {
        $factory = Shortid::getFactory();

        self::assertInstanceOf(Factory::class, $factory);
    }

    public function testSetFactory(): void
    {
        $factory = new Factory();
        Shortid::setFactory($factory);

        self::assertSame($factory, Shortid::getFactory());
    }

    public function testIsValid(): void
    {
        self::assertTrue(Shortid::isValid('shortid'));
    }

    public function testIsNotValid(): void
    {
        self::assertFalse(Shortid::isValid('/(;#!'));
        self::assertFalse(Shortid::isValid('harmful string stuff'));
    }

    public function testIsValidWithRegexChar(): void
    {
        $factory = Shortid::getFactory();
        $factory->setAlphabet('hìjklmnòpqrstùvwxyzABCDEFGHIJKLMNOPQRSTUVWX.\+*?[^]$(){}=!<>|:-/');
        Shortid::setFactory($factory);

        self::assertTrue(Shortid::isValid('slsh/]?'));
    }

    public function testJsonSerializable(): void
    {
        $generated = Shortid::generate();

        self::assertInstanceOf(JsonSerializable::class, $generated);
    }

    public function testJsonEncode(): void
    {
        $generated = Shortid::generate();

        self::assertSame('"'.$generated.'"', \json_encode($generated));
    }

    public function testSerialize(): void
    {
        $shortid = new Shortid('shortid');

        self::assertSame('shortid', $shortid->serialize());
    }

    public function testUnserialize(): void
    {
        $shortid = Shortid::generate();
        $shortid->unserialize('shortid');

        self::assertSame('shortid', (string) $shortid);
    }

    public function testMagicSerialize(): void
    {
        $shortid = new Shortid('shortid');

        self::assertSame(['id' => 'shortid'], $shortid->__serialize());
    }

    public function testMagicUnserialize(): void
    {
        $shortid = Shortid::generate();
        $shortid->__unserialize(['id' => 'shortid']);

        self::assertSame('shortid', (string) $shortid);
    }

    public function testInvalidArgumentInConstructor(): void
    {
        $this->expectException(InvalidShortidException::class);
        new Shortid('an_invalid_too_long_shortid');
    }
}
