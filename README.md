**PHP7**
========
PHP 7 is finally out there with 100 percent improvement in performance speed over PHP 5.6 and we will have a look at couple of interesting features that php 7 offers.

**PHP 7.0.0** was released on  ***03 Dec 2015*** eleven years after its 5.0 release. Subsequently **PHP 7.0.1** was released on  ***15 Dec 2015*** with several bug fixes.

We will be covering following topics

 1. New Features
	a. Scalar type hints
	b. Return type declarations
	c. Anonymous classes
	d. The space ship operator
	e. Null coalesce operator
	f. Throwables
 2. Deprecations & Removals
 3. Syntax Changes


**New Features**
----------------

a. **Scalar type hints**

PHP 5 introduced the ability to require function parameters to be of a certain type. This provides a safeguard against invalid uses, like passing a UNIX timestamp to a method which expects a DateTime object. It also makes it clear to other developers how the function should be used. For example, compare the following method signatures with and without type hints:

``` php
<?php

// No type hint:
function getNextWeekday($date) { /*...*/ }

// Class type hint (PHP 5):
function getNextWeekday(DateTime $date) { /*...*/ }
```

These hints were initially limited to just classes and interfaces, but was soon expanded to allow array and callable types too. PHP 7 extends this further by allowing scalar types like int, float, string and bool:

``` php
<?php

// Scalar type hint (PHP 7):
function getNextWeekday(int $date) { /*...*/ }
```


PHP’s flexible type system is one of its most-useful features, allowing numeric strings to be used as integers and vice-versa. This tradition is continued in PHP 7 but now you have the option of enabling strict type enforcement like you’d see in other languages (such as Java or C#). This setting can be enabled using the declare construct.

```php
<?php

declare(strict_types=1);
```
Here’s a table showing which scalar types are accepted in “Coercive” mode based on the declared type:
| Type declaration  | int  | float  | string   | bool   | object |
|---|---|---|---|---|---|
| int  |  yes |  yes* | yes†  | yes  | no  |
| float  | yes  | yes  | yes†  | yes  | no  |
| string | yes  | yes  | yes  | yes  | yes†   |
| bool  | yes  |  yes | yes  | yes  | no   |

 * Only non-NaN floats between PHP_INT_MIN and PHP_INT_MAX accepted.

 † If it’s a numeric string

 ‡ Only if object has a __toString() method

b. **Return type declarations**

Another important new feature coming with PHP 7 is the ability to define the return type of methods and functions, and it behaves in the same fashion as scalar type hints in regards of coercion and strict mode:

```php
<?php

function a() : bool
{
   return 1;
}
var_dump(a());
```

This snippet will run without warnings and the returned value will be converted to bool automatically. If you enable strict mode (just the same as with scalar type hints), you will get a fatal error instead:

    Fatal error: Uncaught TypeError: Return value of a() must be of the type boolean, integer returned

Allowed types, strict mode
| Type declaration  | int  | float  | string   | bool   | object |
|---|---|---|---|---|---|
| int  |  yes |  no | no  | no  | no  |
| float  | yes*  | yes  | no  | no  | no  |
| string | no  | no  | yes  | no  | no   |
| bool  | no  |  no | no  | yes  | no   |

* Allowed due to widening primitive conversion


c. **Anonymous classes**

For some time PHP has featured anonymous function support in the shape of Closures; PHP7 introduces the same kind of functionality for objects of an anonymous class.

The ability to create objects of an anonymous class is an established and well used part of Object Orientated programming in other languages (namely C# and Java).

An anonymous class might be used over a named class:

 - when the class does not need to be documented
 - when the class is used only once during execution

An anonymous class is a class without a (programmer declared) name. The functionality of the object is no different from that of an object of a named class. They use the existing class syntax, with the name missing:

```php
<?php

/**
 * Anonymous classes
 */
$foo = new class {
    public function foo() {
        return "bar";
    }
};
var_dump($foo,$foo->foo());
```
or
```php
<?php

/**
 * Interface Sum
 */
interface Sum
{
    /**
     * @param int $a
     * @param int $b
     * @return int
     */
    public function getSum(int $a, int $b) : int;
}

/**
 * @return mixed
 */
function retriveSumObject()
{
    return new class() implements Sum{
        /**
         * @param int $a
         * @param int $b
         * @return int
         */
        public function getSum(int $a, int $b) : int
        {
            return $a + $b;
        }
    };
}
var_dump(retriveSumObject()->getSum(1,2)); //int(3)
```

d. **The space ship operator**

PHP 7 introduces a new three-way comparison operator <=> (T_SPACESHIP) which takes two expressions: (expr) <=> (expr). It compares both sides of the expression and, depending on the result, returns one of three values:

| Return Value  |  Reason |
|---|---|
| 0  |  If both expressions are equa |
| 1  |  If the left is greater |
| -1  |  If the right is greater |

You may be familiar with the above table if you’ve worked with existing comparison functions like `strcmp` before.

You can compare everything from scalar values (like ints and floats) to arrays and even objects too. Here are some examples

```php
<?php

// Integers
echo 1 <=> 1; // 0
echo 1 <=> 2; // -1
echo 2 <=> 1; // 1

// Floats
echo 1.5 <=> 1.5; // 0
echo 1.5 <=> 2.5; // -1
echo 2.5 <=> 1.5; // 1

// Strings
echo "a" <=> "a"; // 0
echo "a" <=> "b"; // -1
echo "b" <=> "a"; // 1

echo "a" <=> "aa"; // -1
echo "zz" <=> "aa"; // 1

// Arrays
echo [] <=> []; // 0
echo [1, 2, 3] <=> [1, 2, 3]; // 0
echo [1, 2, 3] <=> []; // 1
echo [1, 2, 3] <=> [1, 2, 1]; // 1
echo [1, 2, 3] <=> [1, 2, 4]; // -1

// Objects
$a = (object) ["a" => "b"];
$b = (object) ["a" => "b"];
echo $a <=> $b; // 0

$a = (object) ["a" => "b"];
$b = (object) ["a" => "c"];
echo $a <=> $b; // -1

$a = (object) ["a" => "c"];
$b = (object) ["a" => "b"];
echo $a <=> $b; // 1

// only values are compared
$a = (object) ["a" => "b"];
$b = (object) ["b" => "b"];
echo $a <=> $b; // 0
```
Perhaps the best application of this operator is to simplify sorting, as functions like usort expect you to perform a comparison and return -1, 0, or 1 accordingly:

This simplification is especially apparent when comparing objects by some property value:

```php
<?php

class Spaceship {
    public $name;
    public $maxSpeed;

    public function __construct($name, $maxSpeed) {
        $this->name = $name;
        $this->maxSpeed = $maxSpeed;
    }
}

$spaceships = [
    new Spaceship('Rebel Transport', 20),
    new Spaceship('Millenium Falcon', 80),
    new Spaceship('X-Wing Starfighter', 80),
    new Spaceship('TIE Bomber', 60),
    new Spaceship('TIE Fighter', 100),
    new Spaceship('Imperial Star Destroyer', 60),
];

// Sort the spaceships by name (in ascending order)
usort($spaceships, function ($ship1, $ship2) {
    return $ship1->name <=> $ship2->name;
});

echo $spaceships[0]->name; // "Imperial Star Destroyer"

// Sort the spaceships by speed (in descending order)
// Notice how we switch the position of $ship1 and $ship2
usort($spaceships, function ($ship1, $ship2) {
    return $ship2->maxSpeed <=> $ship1->maxSpeed;
});

echo $spaceships[0]->name; // "TIE Fighter"
```

Without the comparison operator, these functions would be much more complex:


```php
<?php

usort($spaceships, function ($ship1, $ship2) {
    if ($ship1->maxSpeed == $ship2->maxSpeed) {
        return 0;
    } elseif ($ship1->maxSpeed < $ship2->maxSpeed) {
        return 1;
    } else {
        return -1;
    }
});
```

e. **Null coalesce operator**

The null coalesce operator ( ?? ) also works as a shortcut for a common use case: a conditional attribution that checks if a value is set before using it. In PHP 5, we would usually do something like this:

```php
<?php

$a = isset($b) ? $b : "test";
```

With the null coalesce operator in PHP 7, we can simply use:

```php
<?php

$a = $b ?? "default";
```

It can even be chained:

```php
<?php

$x = ["yarr" => "meaningful_value"];

var_dump($x["aharr"] ?? $x["waharr"] ?? $x["yarr"]); // string(16) "meaningful_value"
```

f. **Throwables**

PHP 7 has introduced exceptions as a replacement for fatal or recoverable fatal errors. These exceptions do not extend Exception, but instead extend a new class BaseException and are named EngineException, TypeException, and ParseException.

```php
<?php

function add(int $left, int $right) {
    return $left + $right;
}

try {
    echo add('left', 'right');
} catch (Exception $e) {
    // Handle or log exception.
}
```

The code above will not catch the TypeException thrown due to the mis-matched type-hint, resulting in the following message to the user:

    Fatal error: Uncaught TypeException: Argument 1 passed to add() must be of the type integer, string given

The reason an object named TypeException would not be caught by catch (Exception $e) is not obvious. The Exception suffix implies that TypeException extends Exception. If the name of the thrown class was TypeError it would be much clearer that the class does not extend Exception, but rather is part of a different class hierarchy that must be caught separately.

To catch the TypeException, the user must write code like this:

```php
<?php

function add(int $left, int $right) {
    return $left + $right;
}

try {
    echo add('left', 'right');
} catch (Exception $e) {
    // Handle exception
} catch (TypeException $e) { // Appears to descend from Exception
    // Log error and end gracefully
}
```

The new exception hierarchy in PHP 7 is as follows:

* interface Throwable
   * Exception implements Throwable
    * Error implements Throwable  (Replaces EngineException)
        * TypeError extends Error
        * ParseError extends Error
        * ArithmeticError extends Error
            * DivisionByZeroError extends ArithmeticError
		        * AssertionError extends Error