**PHP7**
========
PHP 7 is finally out there with 100 percent improvement in performance speed over PHP 5.6 and we will have a look at couple of interesting features that php 7 offers.

**PHP 7.0.0** was released on  ***03 Dec 2015*** eleven years after its 5.0 release. Subsequently **PHP 7.0.1** was released on  ***15 Dec 2015*** with several bug fixes.

We will be covering following topics

 1. New Features
	 * Scalar type hints
	 *  Return type declarations
	 * Anonymous classes
	 * The space ship operator
	 * Null coalesce operator
	 * Throwables
 2. Deprecations & Removals
 3. Syntax Changes


**New Features**
----------------

**a. Scalar type hints**

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
![Typehint][logo]

[logo]: https://github.com/MaharzanNaresh/php7/blob/markdown-prep/typehint.png "Type Hint"

**b. Return type declarations**

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

![ReturnTypeDeclaration][logo]

[logo]: https://github.com/MaharzanNaresh/php7/blob/markdown-prep/returnTypeDeclarations.png "ReturnTypeDeclaration"


**c. Anonymous classes**

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

**d. The space ship operator**

PHP 7 introduces a new three-way comparison operator `<=>` `(T_SPACESHIP)` which takes two expressions: `(expr) <=> (expr)`. It compares both sides of the expression and, depending on the result, returns one of three values:

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

 **e.Null coalesce operator**

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

 **f.Throwables**

PHP 7 has introduced exceptions as a replacement for fatal or recoverable fatal errors. These exceptions do not extend `Exception`, but instead extend a new class `BaseException` and are named `EngineException`, `TypeException`, and `ParseException`.

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

The code above will not catch the `TypeException` thrown due to the mis-matched type-hint, resulting in the following message to the user:

    Fatal error: Uncaught TypeException: Argument 1 passed to add() must be of the type integer, string given

The reason an object named `TypeException` would not be caught by `catch (Exception $e)` is not obvious. The Exception suffix implies that `TypeException` extends `Exception`. If the name of the thrown class was TypeError it would be much clearer that the class does not `extend Exception`, but rather is part of a different class hierarchy that must be caught separately.

To catch the `TypeException`, the user must write code like this:

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

Throwable interface signature

```php
<?php

interface Throwable
{
    public function getMessage();
    public function getCode();
    public function getFile();
    public function getLine();
    public function getTrace();
    public function getTraceAsString();
    public function getPrevious();
    public function __toString();
}

```

**Deprecations & Removals**
----------------

A number of deprecated items have been removed. Because they’ve been deprecated for some time now, hopefully you aren’t using them! This might, however, have an impact on legacy applications.

In particular, ASP-style tags `( <%, <%= and %> )`, were removed along with script tags `( <script language=”php”> )`. Make sure you are using the recommended `<?php` tag instead. Other functions that were previously deprecated, like split, have also been removed in PHP 7.

The ereg extension (and all ereg_* functions) have been deprecated since PHP 5.3. It should be replaced with the PCRE extension (preg_* functions), which offers many more features. The mysql extension (and the mysql_* functions) have been deprecated since PHP 5.5. For a direct migration, you can use the mysqli extension and the mysqli_* functions instead.

**Left-to-right parsing**

Indirect variable, property and method references are now interpreted with left-to-right semantics. Some examples:
```php
<?php

$$foo['bar']['baz'] // interpreted as ($$foo)['bar']['baz']
  $foo->$bar['baz']   // interpreted as ($foo->$bar)['baz']
  $foo->$bar['baz']() // interpreted as ($foo->$bar)['baz']()
  Foo::$bar['baz']()  // interpreted as (Foo::$bar)['baz']()
```
To restore the previous behavior add explicit curly braces:

```php
<?php

  ${$foo['bar']['baz']}
  $foo->{$bar['baz']}
  $foo->{$bar['baz']}()
  Foo::{$bar['baz']}()
```

**Global Keywords**

The global keyword now only accepts simple variables. Instead of

```php
<?php

 global $$foo->bar;
```
it is now required to write the following:
```php
<?php

global ${$foo->bar};
```


**Parenthesis influencing behavior**

Parentheses around variables or function calls no longer have any influence on behavior. For example the following code, where the result of a function call is passed to a by-reference function

```php
<?php

function getArray() { return [1, 2, 3]; }

  $last = array_pop(getArray());
  // Strict Standards: Only variables should be passed by reference
  $last = array_pop((getArray()));
  // Strict Standards: Only variables should be passed by reference
```

will now throw a strict standards error regardless of whether parentheses are used. Previously no notice was generated in the second case.


**By-reference assignment ordering**
Array elements or object properties that are automatically created during by-reference assignments will now result in a different order. For example

```php
<?php

  $array = [];
  $array["a"] =& $array["b"];
  $array["b"] = 1;
  var_dump($array);
```
now results in the array `[“a” ⇒ 1, “b” ⇒ 1]`, while previously the result was `[“b” ⇒ 1, “a” ⇒ 1]`;



**list() behavior**

Variable assignment order
`list()` will no longer assign variables in reverse order. For example


```php
<?php

list($array[], $array[], $array[]) = [1, 2, 3];
  var_dump($array);
```
will now result in `$array == [1, 2, 3]` rather than `[3, 2, 1]`. Note that only the order of the assignments changed, but the assigned values stay the same. E.g. a normal usage like

```php

<?php


  list($a, $b, $c) = [1, 2, 3];
  // $a = 1; $b = 2; $c = 3;
```
will retain its current behavior.


**Empty list assignments**
Empty list() assignments are no longer allowed. As such all of the following are invalid:

```php
<?php


  list() = $a;
  list(,,) = $a;
  list($x, list(), $y) = $a;
```
`list()` no longer supports unpacking strings (while previously this was only supported in some cases). The code

```php
<?php

$string = "xy";
list($x, $y) = $string;
```
will now result in `$x == null and $y == null` (without notices) instead of `$x == “x” and $y == “y”`. Furthermore list() is now always guaranteed to work with objects implementing ArrayAccess, e.g.

```php
<?php

 list($a, $b) = (object) new ArrayObject([0, 1]);
```
will now result in `$a == 0 and $b == 1`. Previously both `$a and $b` were null.

**foreach behavior**



----------

*Interaction with internal array pointers*


----------

Iteration with foreach() no longer has any effect on the internal array pointer, which can be accessed through the current()/next()/etc family of functions. For example

```php

<?php


  $array = [0, 1, 2];
  foreach ($array as &$val) {
      var_dump(current($array));
  }

```
will now print the value int(0) three times. Previously the output was int(1), int(2) and bool(false).



----------
*Array iteration by-value*


----------

When iterating arrays by-value, foreach will now always operate on a copy of the array, as such changes to the array during iteration will not influence iteration behavior. For example

```php

<?php


  $array = [0, 1, 2];
  $ref =& $array; // Necessary to trigger the old behavior
  foreach ($array as $val) {
      var_dump($val);
      unset($array[1]);
  }

```
will now print all three elements (0 1 2), while previously the second element 1 was skipped (0 2).



----------
*Array iteration by-reference*


----------

When iterating arrays by-reference, modifications to the array will continue to influence the iteration. However PHP will now do a better job of maintaining a correct position in a number of cases. E.g. appending to an array during by-reference iteration

```php

<?php


  $array = [0];
  foreach ($array as &$val) {
      var_dump($val);
      $array[1] = 1;
  }

```
will now iterate over the appended element as well. As such the output of this example will now be `“int(0) int(1)”`, while previously it was only `“int(0)”`.


**Parameter handling**



----------
*Duplicate parameter names*


----------

It is no longer possible to define two function parameters with the same name. For example, the following method will trigger a compile-time error:

```php

<?php


  public function foo($a, $b, $unused, $unused) {
      // ...
  }

```

Code like this should be changed to use distinct parameter names, for example:

```php
<?php


  public function foo($a, $b, $unused1, $unused2) {
      // ...
  }
```



----------
*Retrieving argument values*


----------

The `func_get_arg()` and `func_get_args()` functions will no longer return the original value that was passed to a parameter and will instead provide the current value (which might have been modified). For example

```php

<?php


  function foo($x) {
      $x++;
      var_dump(func_get_arg(0));
  }
  foo(1);
```
will now print “2” instead of “1”. This code should be changed to either perform modifications only after calling `func_get_arg(s)`

```php
<?php


  function foo($x) {
      var_dump(func_get_arg(0));
      $x++;
  }
```
or avoid modifying the parameters altogether:

```php
<?php

  function foo($x) {
      $newX = $x + 1;
      var_dump(func_get_arg(0));
  }
```



----------
*Effect on backtraces*


----------

Similarly exception backtraces will no longer display the original value that was passed to a function and show the modified value instead. For example

```php
<?php


  function foo($x) {
      $x = 42;
      throw new Exception;
  }
  foo("string");

```
will now result in the stack trace

```php

  Stack trace:
  #0 file.php(4): foo(42)
  #1 {main}

```
while previously it was:

```php

  Stack trace:
  #0 file.php(4): foo('string')
  #1 {main}

```
While this should not impact runtime behavior of your code, it is worthwhile to be aware of this difference for debugging purposes.

The same limitation also applies to `debug_backtrace()` and other functions inspecting function arguments.

**Standard Library Changes**

* `substr()` now returns an empty string instead of `FALSE` when the truncation happens on boundaries.
* `call_user_method()` and `call_user_method_array()` no longer exists.
* `ob_start()` no longer issues an `E_ERROR`, but instead an `E_RECOVERABLE_ERROR` in case an output buffer is created in an output buffer handler.
* The internal sorting algorithm has been improved, what may result in different sort order of elements that compare as equal.
* Removed `dl()` function on fpm-fcgi.
* `setcookie()` with an empty cookie name now issues an `E_WARNING` and doesn’t send an empty set-cookie header line anymore.