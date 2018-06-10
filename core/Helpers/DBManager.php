<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:27
 */
class DBManager
{
    public $connection;
    public function __construct()
    {
        $this->connection = new PDO(Config::getDB());
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
