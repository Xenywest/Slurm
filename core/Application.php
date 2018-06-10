<?php

/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:20
 */
class Application
{
    public $command_arguments = array();

    public function __construct($arguments)
    {
        $this->command_arguments = $arguments;
    }

    public function start()
    {
        if(Security::isWhitelisted($this->command_arguments[C::COMMAND]))
        {
            $controller = Helper::getController($this->command_arguments[C::COMMAND]);
            $controller::initialize($this->command_arguments);
        }
        else
        {
            Logger::log("Error\nNot whitelisted controller\nSyntax: php app.php <add|delete|show> <params>");
        }
    }
}