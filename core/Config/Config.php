<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 10.06.2018
 * Time: 20:26
 */


include __DIR__ . '../Helpers/C.php';
include __DIR__ . '../Helpers/DBManager.php';
include __DIR__ . '../Helpers/Squeue.php';

include __DIR__ . '../Models/Tokenization.php';

include __DIR__ . '../Controllers/Controller.php';
include __DIR__ . '../Controllers/AddRelate.php';
include __DIR__ . '../Controllers/Notifier.php';

include __DIR__ . '../Application.php';

class Config
{
    public static function getDB()
    {
        return 'sqlite:' . __DIR__ . 'sqlite.db';
    }

}
