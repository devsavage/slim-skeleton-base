<?php

if(!function_exists("env")) {
    function env($key, $default = null)
    {
        if(array_key_exists($key, $_ENV)) {
            $value = $_ENV[$key];
            return $value;
        }

        return $default;
    }
}

if (!function_exists("dd")) {
    function dd()
    {
        array_map(function($x) { 
            dump($x); 
        }, func_get_args());
        die;
    }
 }
