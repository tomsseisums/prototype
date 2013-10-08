<?php namespace Prototype;

use Exception;

abstract class Extendable extends Prototype
{
    /**
     * Added extensions.
     * @var array
     */
    protected static $extensions = array();

    /**
     * Registers a new extension.
     * @param  Extension $extension
     * @return void
     */
    public static function registerExtension($extension)
    {
        if (class_exists($extension, false) && array_key_exists(__NAMESPACE__ . '\Extension', class_parents($extension)))
        {
            static::$extensions[] = $extension;
        }
        else
        {
            throw new Exception('Argument 1 passed to ' . __METHOD__ . ' must be an instance of Extension');
        }
    }

    /**
     * Creates new object and instantiates extensions.
     */
    public function __construct()
    {
        foreach (static::$extensions as $extension)
        {
            new $extension($this);
        }
    }
}