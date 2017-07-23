<?php

namespace PUGX\Shortid;

class Shortid
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Factory
     */
    private static $factory;

    /**
     * @param string $id
     */
    public function __construct(string $id = null)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }

    /**
     * @param int|null    $length
     * @param string|null $alphabet
     *
     * @return self
     */
    public static function generate(int $length = null, string $alphabet = null): self
    {
        if (!is_null($length)) {
            self::getFactory()->checkLength($length);
        }
        self::getFactory()->checkAlphabet($alphabet);

        return self::getFactory()->generate($length, $alphabet);
    }

    /**
     * @return Factory
     */
    public static function getFactory(): Factory
    {
        if (null === self::$factory) {
            self::$factory = new Factory();
        }

        return self::$factory;
    }

    /**
     * @param Factory
     */
    public static function setFactory(Factory $factory = null)
    {
        self::$factory = $factory;
    }

    /**
     * @param string $value
     * @param int    $length
     * @param string $alphabet
     *
     * @return bool
     */
    public static function isValid(string $value, int $length = null, string $alphabet = null): bool
    {
        $length = is_null($length) ? self::getFactory()->getLength() : $length;
        $alphabet = preg_quote($alphabet ?: self::getFactory()->getAlphabet(), '/');
        $matches = [];
        $ok = preg_match('/(['.$alphabet.']{'.$length.'})/', $value, $matches);

        return $ok > 0 && strlen($matches[0]) == $length;
    }
}
