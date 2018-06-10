<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 11.06.2018
 * Time: 2:08
 */
class Helper
{

    public static function getController($command)
    {
        $controllers = array('add' => 'AddRelate',
            'delete' => 'DeleteRelate',
            'show' => 'ShowRelate',
            'notify' => 'Notifier',
        );
        return $controllers[$command];
    }

}