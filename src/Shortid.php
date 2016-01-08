<?php

namespace PUGX\Shortid;

class Shortid
{
    /**
     * @var Factory
     */
    private static $factory;

    /**
     * @param int    $length
     * @param string $alphabet
     *
     * @return string
     */
    public static function generate($length = null, $alphabet = null)
    {
        self::getFactory()->checkLength($length);
        self::getFactory()->checkAlphabet($alphabet);

        return self::getFactory()->generate($length, $alphabet);
    }

    /**
     * @return Factory
     */
    public static function getFactory()
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
    public static function isValid($value, $length = null, $alphabet = null)
    {
        $length = is_null($length) ? self::getFactory()->getLength() : $length;
        $alphabet = preg_quote($alphabet ?: self::getFactory()->getAlphabet());
        $matches = [];
        $ok = preg_match('/(['.$alphabet.']{'.$length.'})/', preg_quote($value, '/'), $matches);

        return $ok > 0 && strlen($matches[0]) == $length;
    }
}
