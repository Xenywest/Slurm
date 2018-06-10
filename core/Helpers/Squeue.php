<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:26
 */
class Squeue
{
    public static function execute()
    {
        $output = array();
        exec('squeue', $output);
        return $output;
    }
}
