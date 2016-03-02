<?php


function add(int $left, int $right) {
    return $left + $right;
}

try {
    echo add('left', 'right');
} catch (Exception $e) {
    echo "Exception:\t ".$e->getMessage();
} catch (Error $e) { // Clearly a different type of object
    echo "Error:\t ".$e->getMessage();
}