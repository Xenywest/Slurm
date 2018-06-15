<?php

//Put here FULL path to /core/Config/Config.php
// __DIR__ means - CURRENT DIR
$full_path_to_config = __DIR__.'/core/Config/Config.php';

include $full_path_to_config;

$arguments = $_SERVER['argv'];

$app = new Application($arguments);
$app->start();
