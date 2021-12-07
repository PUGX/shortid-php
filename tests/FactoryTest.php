<?php

namespace PUGX\Shortid\Test;

use PHPUnit\Framework\TestCase;
use PUGX\Shortid\Factory;
use RandomLib\Factory as RandomLibFactory;

final class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    protected function setUp(): void
    {
        $this->factory = new Factory();
    }

    public function testGenerate(): void
    {
        $generated = $this->factory->generate();

        $this->assertRegExp('/^[a-z0-9\_\-]{7,7}$/i', $generated->__toString());
    }

    /**
     * @return string[][]
     */
    public function alphabetsProvider(): array
    {
        $alphabets = [];
        $chars = [];

        for ($i = 1; $i <= 65533; ++$i) {
            $chars[] = \mb_convert_encoding("&#$i;", 'UTF-8', 'HTML-ENTITIES');
        }
        /** @var array<string> $chars */
        $chars = \preg_replace('/[^\p{Ll}]/u', '', $chars);
        $chars = \array_filter(\array_map('trim', $chars));

        for ($i = 0; $i < 100; ++$i) {
            \shuffle($chars);
            $alphabets[] = [\implode('', \array_slice($chars, 0, 64))];
        }

        return $alphabets;
    }

    /**
     * @dataProvider alphabetsProvider
     */
    public function testSetAlphabet(string $alphabet): void
    {
        $this->factory->setAlphabet($alphabet);

        $newAlphabet = $this->factory->getAlphabet();

        $this->assertSame($alphabet, $newAlphabet);
    }

    /**
     * @dataProvider wrongAlphabetsProvider
     */
    public function testSetWrongAlphabet(string $alphabet): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->factory->setAlphabet($alphabet);
    }

    /**
     * @return array{test: array{'test'}, rand: array{string}}
     */
    public function wrongAlphabetsProvider(): array
    {
        return [
            'test' => ['test'],
            'rand' => [\sha1((string) \random_int(0, \getrandmax()))],
        ];
    }

    public function testGetFactory(): void
    {
        $factory = Factory::getFactory();

        $this->assertInstanceOf(RandomLibFactory::class, $factory);
    }

    public function testSetLength(): void
    {
        $this->factory->setLength(5);
        $this->assertSame(5, $this->factory->getLength());
    }

    public function testCheckLength(): void
    {
        $null = $this->factory->checkLength(null, false);
        $this->assertNull($null);
    }

    public function testSetWrongLengthType(): void
    {
        $this->expectException(\TypeError::class);

        $this->factory->setLength('invalid');
    }

    public function testSetWrongLengthRange(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->factory->setLength(0);
    }
}
