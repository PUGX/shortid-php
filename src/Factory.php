<?php

namespace PUGX\Shortid;

use RandomLib\Factory as RandomLibFactory;

class Factory
{
    /**
     * @var string
     */
    private $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';

    /**
     * @var RandomLibFactory
     */
    private static $factory;

    /**
     * @return string
     */
    public function generate()
    {
        return self::getFactory()->getMediumStrengthGenerator()->generateString(7, $this->alphabet);
    }

    /**
     * @param string $alphabet
     */
    public function setAlphabet($alphabet)
    {
        $length = mb_strlen($alphabet, 'UTF-8');
        if (64 !== $length) {
            throw new \InvalidArgumentException(sprintf('Invalid alphabet: %s (length: %u)', $alphabet, $length));
        }
        $this->alphabet = $alphabet;
    }

    /**
     * @return string
     */
    public function getAlphabet()
    {
        return $this->alphabet;
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
}
