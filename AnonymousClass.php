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
var_dump(retriveSumObject()->getSum(1,2));