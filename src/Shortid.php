<?php

namespace PUGX\Shortid;

class Shortid
{
    /**
     * @var Factory
     */
    private static $factory;

    /**
     * @return string
     */
    public static function generate()
    {
        return self::getFactory()->generate();
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
     *
     * @return bool
     */
    public static function isValid($value)
    {
        $alphabet = preg_quote(self::getFactory()->getAlphabet());
        $matches = [];
        $ok = preg_match('/(['.$alphabet.']{5})/', preg_quote($value, '/'), $matches);

        return $ok > 0 && strlen($matches[0]) == 5;
    }
}
