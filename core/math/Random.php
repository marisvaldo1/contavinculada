<?php

namespace math;

class Random
{
    /**
     * @param $digits
     * @return string
     */
    public static function number($digits)
    {
        $out = '';
        for ($i = 0; $i < $digits; $i++) {
            $out .= random_int(0, 9);
        }
        return $out;
    }

    public static function alpha($characters)
    {
        $out = '';
        $alphabet = 'abcdefghijklmnopqrstuvwxyz';
        $len = mb_strlen($alphabet);
        for ($i = 0; $i < $characters; $i++) {
            $out .= $alphabet[random_int(0, $len - 1)];
        }
        return $out;
    }

}