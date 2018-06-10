<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:22
 */
abstract class Controller
{
    private $database;

    public $arguments;

    //!TODO move it AWAY to Model class
    public function __construct()
    {
        $this->database = new DBManager();
    }

    public static function initialize($command_line)
    {
        $class = new static;
        return $class->service($command_line);
    }

    public function service($command_line)
    {
        $this->arguments = $command_line;

        if($this->validate($this->arguments))
        {
            $this->action();
        }
    }

    abstract public function validate($command_line);

    abstract public function action();

    public function getDatabase()
    {
        return $this->database;
    }

}