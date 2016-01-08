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
     * @return string
     */
    public function generate($length = null, $alphabet = null)
    {
        $length = is_null($length) ? $this->length : $length;
        $alphabet = is_null($alphabet) ? $this->alphabet : $alphabet;

        return self::getFactory()->getMediumStrengthGenerator()->generateString($length, $alphabet);
    }

    /**
     * @param string $alphabet
     */
    public function setAlphabet($alphabet)
    {
        $this->checkAlphabet($alphabet, true);
        $this->alphabet = $alphabet;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->checkLength($length);
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getAlphabet()
    {
        return $this->alphabet;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return RandomLibFactory
     */
    public static function getFactory()
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
    public function checkLength($length, $strict = false)
    {
        if (is_null($length) && !$strict) {
            return;
        }
        if (!is_int($length)) {
            throw new \InvalidArgumentException(sprintf('Invalid type: %s', gettype($length)));
        }
        if ($length < 2 || $length > 20) {
            throw new \InvalidArgumentException('Invalid length.');
        }
    }

    /**
     * @param string $length
     * @param bool   $strict
     */
    public function checkAlphabet($alphabet = null, $strict = false)
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
