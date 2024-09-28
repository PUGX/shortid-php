<?php

namespace PUGX\Shortid;

use Random\Randomizer;
use RandomLib\Factory as RandomLibFactory;
use RandomLib\Generator;

final class Factory
{
    /**
     * @var int
     */
    private $length = 7;

    /**
     * @var string
     */
    private $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';

    /**
     * @var bool
     */
    private $readable = false;

    /**
     * @var RandomLibFactory
     */
    private static $factory;

    /**
     * @throws InvalidShortidException
     */
    public function generate(int $length = null, string $alphabet = null, bool $readable = null): Shortid
    {
        $length = $length ?? $this->length;
        $readable = $readable ?? $this->readable;
        if (null === $alphabet && $readable) {
            $alphabet = \str_replace(\str_split(Generator::AMBIGUOUS_CHARS), '', $this->alphabet);
            $alphabet .= \str_repeat('_', \strlen(Generator::AMBIGUOUS_CHARS) / 2);
            $alphabet .= \str_repeat('-', \strlen(Generator::AMBIGUOUS_CHARS) / 2);
        }
        if (\PHP_VERSION_ID >= 80300) {
            $id = (new Randomizer())->getBytesFromString($alphabet ?? $this->alphabet, $length);
        } else {
            $id = self::getFactory()->getMediumStrengthGenerator()->generateString($length, $alphabet ?? $this->alphabet);
        }

        return new Shortid($id, $length, $alphabet);
    }

    public function setAlphabet(string $alphabet): void
    {
        $this->checkAlphabet($alphabet, true);
        $this->alphabet = $alphabet;
    }

    public function setLength(int $length): void
    {
        $this->checkLength($length);
        $this->length = $length;
    }

    public function getAlphabet(): string
    {
        return $this->alphabet;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public static function getFactory(): RandomLibFactory
    {
        if (null === self::$factory) {
            self::$factory = new RandomLibFactory();
        }

        return self::$factory;
    }

    public function checkLength(int $length = null, bool $strict = false): void
    {
        if (null === $length && !$strict) {
            return;
        }
        if ($length < 2 || $length > 20) {
            throw new \InvalidArgumentException('Invalid length.');
        }
    }

    public function checkAlphabet(?string $alphabet = null, bool $strict = false): void
    {
        if (null === $alphabet && !$strict) {
            return;
        }
        $alphaLength = null === $alphabet ? 0 : \mb_strlen($alphabet, 'UTF-8');
        if (64 !== $alphaLength) {
            throw new \InvalidArgumentException(\sprintf('Invalid alphabet: %s (length: %u)', $alphabet, $alphaLength));
        }
    }

    public function setReadable(bool $readable): void
    {
        $this->readable = $readable;
    }
}
