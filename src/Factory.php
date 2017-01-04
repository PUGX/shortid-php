<?php

namespace PUGX\Shortid;

use RandomLib\Factory as RandomLibFactory;

class Factory
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
     * @var RandomLibFactory
     */
    private static $factory;

    /**
     * @param int    $length
     * @param string $alphabet
     *
     * @return Shortid
     */
    public function generate(int $length = null, string $alphabet = null): Shortid
    {
        $length = is_null($length) ? $this->length : $length;
        $alphabet = is_null($alphabet) ? $this->alphabet : $alphabet;
        $id = self::getFactory()->getMediumStrengthGenerator()->generateString($length, $alphabet);

        return new Shortid($id);
    }

    /**
     * @param string $alphabet
     */
    public function setAlphabet(string $alphabet)
    {
        $this->checkAlphabet($alphabet, true);
        $this->alphabet = $alphabet;
    }

    /**
     * @param int $length
     */
    public function setLength(int $length)
    {
        $this->checkLength($length);
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getAlphabet(): string
    {
        return $this->alphabet;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return RandomLibFactory
     */
    public static function getFactory(): RandomLibFactory
    {
        if (null === self::$factory) {
            self::$factory = new RandomLibFactory();
        }

        return self::$factory;
    }

    /**
     * @param int  $length
     * @param bool $strict
     */
    public function checkLength(int $length = null, bool $strict = false)
    {
        if (is_null($length) && !$strict) {
            return;
        }
        if ($length < 2 || $length > 20) {
            throw new \InvalidArgumentException('Invalid length.');
        }
    }

    /**
     * @param string $length
     * @param bool   $strict
     */
    public function checkAlphabet(string $alphabet = null, bool $strict = false)
    {
        if (is_null($alphabet) && !$strict) {
            return;
        }
        $alphaLength = mb_strlen($alphabet, 'UTF-8');
        if (64 !== $alphaLength) {
            throw new \InvalidArgumentException(sprintf('Invalid alphabet: %s (length: %u)', $alphabet, $alphaLength));
        }
    }
}
