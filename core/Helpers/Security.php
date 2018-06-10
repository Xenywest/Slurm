<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 11.06.2018
 * Time: 2:05
 */
class Security
{
    public static function isWhitelisted($command)
    {
        $whitelisted_controllers = array('add','show','delete','notify');
        if(\in_array($command, $whitelisted_controllers, true))
        {
            return true;
        }
        return false;
    }
}