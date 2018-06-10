<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:26
 */


require_once __DIR__ . '/../Helpers/C.php';
require_once __DIR__ . '/../Helpers/Helper.php';
require_once __DIR__ . '/../Helpers/Security.php';
require_once __DIR__ . '/../Helpers/Squeue.php';

require_once __DIR__ . '/../Models/Tokenization.php';
require_once __DIR__ . '/../Services/Sender.php';

require_once __DIR__ . '/../Controllers/Controller.php';
require_once __DIR__ . '/../Controllers/AddRelate.php';
require_once __DIR__ . '/../Controllers/Notifier.php';

require_once __DIR__ . '/../Application.php';


require_once __DIR__ . '/../vendor/php-activerecord/ActiveRecord.php';




class Config
{
    //SETUP BLOCK

    //CHOOSE DB: sqlite OR mysql OR pgsql
    public static $db_type = 'sqlite';

    //SQLite information
    //database path from current path /core/Config/Config.php
    private static $sqlite_database = '/sqlite.db';


    //MYSQL information
    //user
    private static $mysql_user = 'root';
    //password
    private static $mysql_password = 'root';
    //database
    private static $mysql_database = 'database';
    //address
    private static $mysql_address = 'localhost';

    //POSTGRESQL information

    //user
    private static $pgsql_user = 'root';
    //password
    private static $pgsql_password = 'root';
    //address
    private static $pgsql_address = 'localhost';
    //database
    private static $pgsql_database = 'database';

    /**
     * @return string
     */
    private static function getMySQLDB()
    {
        return 'mysql://'. self::$mysql_user . ':' . self::$mysql_password . '@' .
            self::$mysql_address . '/' . self::$mysql_database;
    }

    private static function getPGSQLDB()
    {
        return 'pgsql://'. self::$pgsql_user . ':' . self::$pgsql_password . '@' .
            self::$pgsql_address . '/' . self::$pgsql_database;
    }

    private static function getSQLiteDB()
    {
        return 'sqlite://'. self::$sqlite_database;
    }

    public static function getConnections()
    {
        return array('mysql' => self::getMySQLDB(),
            'pgsql' => self::getPGSQLDB(),
            'sqlite' => self::getSQLiteDB());
    }

}

ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory('../Models');
    $cfg->set_connections(Config::getConnections());
    $cfg->set_default_connection(Config::$db_type);
});