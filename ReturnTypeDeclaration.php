<?php
declare(strict_types=1);

class ReturnTypeDeclaration
{
    public function getSum($a, $b) :int
    {
        return $a + $b;
    }
}

$obj = new ReturnTypeDeclaration();
var_dump($obj->getSum(1, 2));
var_dump($obj->getSum(1, 1.11));