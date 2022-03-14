<?php namespace App\Libraries\Classes;

class Random
{
    public static function array_random(array $array, int $n = 1): array
    {
        if ($n < 1 || $n > count($array)) {
            return $array;
        }
    
        return ($n !== 1)
            ? array_values(array_intersect_key($array, array_flip(array_rand($array, $n))))
            : array($array[array_rand($array)]);
    }
}
