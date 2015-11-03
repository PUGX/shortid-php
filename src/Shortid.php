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
    public static function setFactory(Factory $factory)
    {
        self::$factory = $factory;
    }

    /**
     * @return bool
     */
    public static function isValid()
    {
        return true;    // TODO
    }
}
