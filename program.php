<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 06.06.2018
 * Time: 22:21
 */

include __DIR__ . '/core/Config/Config.php';

$arguments = $_SERVER['argv'];

$app = new Application($arguments);
$app->start();
