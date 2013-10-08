### Prototype
Prototype is a runtime extension library built for plugin development.  
Initially inspired by JavaScript's `.prototype`, but extended to allow for
instance extensions.

### Requirements
- PHP5.4

### Contents
You have three abstract classes at your disposal:
- `Prototype` &mdash; the main runtime extension component, used to extend
classes with public properties and methods.
- `Extendable` (extends `Prototype`) &mdash; the main instance level runtime extension component, used
to register class based extensions that will be auto-applied to every instance.
- `Extension` &mdash; not exactly for extensions, acts as an interface.
Used in conjuction with `Extendable`.

### Usage
`Prototype`:

```php
use Prototype\Prototype as Proto;

// Simply extend with Prototype.
class A extends Proto
{}

// Create instance.
$a = new A;

// Add property.
$a->dummyProperty = 'x';

// Add method.
$a->dummyMethod = function()
{
    // Yes, $this actually refers to the classes instance.
    echo $this->dummyProperty;

    // Chaining is also possible.
    return $this;
};

// Test it.
$a->dummyMethod()->dummyMethod(); // xx
```

### TODO
- Finish Usage:
 - `Extendable` & `Extension`.