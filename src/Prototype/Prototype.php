<?php namespace Prototype;

use Exception;
use Closure;

abstract class Prototype
{
    /**
     * Dynamic methods.
     * @var array
     */
    protected $methods = array();
    
    /**
     * Dynamic properties.
     * @var array
     */
    protected $properties = array();

    /**
     * Register dynamics.
     * @param string $property
     * @param mixed $value
     */
    final public function __set($property, $value)
    {
        // Determine whether it's a method that's about to be added and, see if it does not exist.
        $is_method = ($value instanceof Closure || is_callable($value)) && !method_exists($this, $property);

        // Determine whether it's a property that's about to be added and, see if it does not exist.
        $is_property = !($value instanceof Closure || is_callable($value)) && !property_exists($this, $property);

        // Add dynamic method.
        if ($is_method)
        {           
            if ($value instanceof Closure)
            {
                // We are binding the closure to $this instance,
                // but preventing it from protected and private access.
                // This allows us to continue chaining.
                $value = $value->bindTo($this);

                // Add the bounded method.
                $this->methods[$property] = $value;
            }

            // Add from callable.
            else
            {
                // Apparently, we cannot bind these,
                // so we will simply append to list of methods.
                $this->methods[$property] = $value;
            }
        }

        // Otherwise we dispatch to properties.
        else if ($is_property)
        {
            $this->properties[$property] = $value;
        }

        // It appears that that's an existing member of the class.
        else
        {
            // Get class name and/or namespace of master class.
            $class = get_class($this);

            // Inform about existing method.
            if ($is_method)
            {
                throw new Exception('Cannot redeclare method ' . implode('::', array($class, $property)));
            }

            // Inform about existing property.
            else
            {
                // Make it seem like PHP.
                $property = '$' . $property;

                throw new Exception('Cannot redeclare property ' . implode('::', array($class, $property)));
            }
        }
    }

    /**
     * Access dynamics.
     *
     * Defined as reference to return result as a reference. (http://www.php.net/manual/en/language.references.return.php)
     * Return as reference required to properly work with dynamic arrays and objects.
     * @return mixed
     */
    final public function &__get($property)
    {
        // First, we attempt to lookup if such property exists,
        // and attempt to return it's value.
        if (array_key_exists($property, $this->properties))
        {
            return $this->properties[$property];
        }

        // In some cases, we might want to work with method pointers.
        else if (array_key_exists($property, $this->methods))
        {
            return $this->methods[$property];
        }

        // Property exists, so appears to be either protected or private.
        else if (property_exists($this, $property))
        {
            // Get the class and/or namespace name of master class.
            $class = get_class($this);

            // Make it seem like PHP.
            $property = '$' . $property;

            throw new Exception('Cannot access private/protected property ' . implode('::', array($class, $property)));
        }

        // We couldn't find anything...
        else
        {
            // Get the class and/or namespace name of master class.
            $class = get_class($this);

            // Make it seem like PHP.
            $property = '$' . $property;

            throw new Exception('Undefined property '. implode('::', array($class, $property)));
        }
    }

    /**
     * Dynamic method caller.
     * @param  string $method
     * @param  array $arguments
     * @return mixed
     */
    final public function __call($method, array $arguments)
    {
        // For an existing method, we simply forward the call to call_user_func
        // while binding values from array.
        if (array_key_exists($method, $this->methods))
        {
            return call_user_func_array($this->methods[$method], $arguments);
        }
        else
        {
            // Get the class and/or namespace name of master class.
            $class = get_class($this);

            throw new Exception('Call to undefined method ' . implode('::', array($class, $method)));
        }
    }
}