<?php


function add(int $left, int $right) {
    return $left + $right;
}

function subtract(int $a, int $b):int {
    return $a - $b;
}

try {
    echo add('left', 'right');
} catch (Exception $e) {
    echo "Exception:\t ".$e->getMessage();
} catch (Error $e) { // Clearly a different type of object
    echo "Error:\t ".$e->getMessage();
}
//Error as Throwable
try {
    sqdf();
} catch (Throwable $t) {
    echo "Throwable: ".$t->getMessage().PHP_EOL;
}
//Exception as Throwable
try {
    throw new Exception("Bla");
} catch (Throwable $t) {
    echo "Throwable: ".$t->getMessage().PHP_EOL;
}
//Error
try {
    sqdf();
} catch (Error $e) {
    echo "Error: ".$e->getMessage().PHP_EOL;
} catch (Exception $e) {
    echo "Exception: ".$e->getMessage().PHP_EOL;
}
//Exception
try {
    throw new Exception("Bla");
} catch (Error $e) {
    echo "Error: ".$e->getMessage().PHP_EOL;
} catch (Exception $e) {
    echo "Exception: ".$e->getMessage().PHP_EOL;
}
//Type error
try {
    echo subtract(array(), array());
} catch (TypeError $t) {
    echo "Type error: ".$t->getMessage().PHP_EOL;
}