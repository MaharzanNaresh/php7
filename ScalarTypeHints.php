<?php
declare(strict_types=1);

class WithoutTypeHint
{
    public function getFactor($data, $multiplier)
    {
        $result = 0;
        foreach($data as $val){
            $result += ($val * $multiplier);
        }

        return $result;
    }

}

class WithPHP5TypeHint
{
    public function getFactor(array $data, $multiplier)
    {
        $result = 0;
        foreach($data as $val){
            $result += ($val * $multiplier);
        }

        return $result;
    }
}
class WithPHP7TypeHint
{
    public function getFactor($data, int $multiplier)
    {
        $result = 0;
        foreach($data as $val){
            $result += ($val * $multiplier);
        }
        return $result;
    }
}

// No type hint:
$withoutTypeHint = new WithoutTypeHint();
$result = $withoutTypeHint->getFactor([1,2,3,4,5], 2);
var_dump($result);

// Class type hint (PHP 5):
$withPHP5TypeHint = new WithPHP5TypeHint();
$result = $withPHP5TypeHint->getFactor([1,2,3,4,5], '2');
var_dump($result);

// Scalar type hint (PHP 7):
$withPHP7TypeHint = new WithPHP7TypeHint();
$result = $withPHP7TypeHint->getFactor([1,2,3,4,5], 2);
var_dump($result);