<?php

namespace PUGX\Shortid;

final class Shortid implements \JsonSerializable, \Serializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Factory|null
     */
    private static $factory;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public static function generate(int $length = null, string $alphabet = null, bool $readable = false): self
    {
        if (null === $length) {
            self::getFactory()->checkLength($length);
        }
        self::getFactory()->checkAlphabet($alphabet);

        return self::getFactory()->generate($length, $alphabet, $readable);
    }

    public static function getFactory(): Factory
    {
        if (null === self::$factory) {
            self::$factory = new Factory();
        }

        return self::$factory;
    }

    public static function setFactory(Factory $factory = null): void
    {
        self::$factory = $factory;
    }

    public static function isValid(string $value, int $length = null, string $alphabet = null): bool
    {
        $length = $length ?? self::getFactory()->getLength();
        $alphabet = \preg_quote($alphabet ?: self::getFactory()->getAlphabet(), '/');
        $matches = [];
        $ok = \preg_match('/^(['.$alphabet.']{'.$length.'})$/', $value, $matches);

        return $ok > 0 && \strlen($matches[0]) === $length;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->id;
    }

    public function serialize(): string
    {
        return $this->id;
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $this->id = $serialized;
    }
}
