<?php namespace Prototype;

abstract class Extension
{
    /**
     * Extendable target.
     * @var Extendable
     */
    protected $extendable;

    /**
     * Creates new Extension.
     * @param Extendable $extendable
     */
    public function __construct(Extendable $extendable)
    {
        $this->extendable = $extendable;

        // Call initializator, if exists.
        if (method_exists($this, 'initialize'))
        {
            call_user_func(array($this, 'initialize'));
        }
    }
}