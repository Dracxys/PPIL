<?php


session_start();
require 'vendor/autoload.php';

$app = new \Slim\Slim ();

\PPIL\utils\ConnectionFactory::setConfig('db.ppil.conf.ini');
\PPIL\utils\ConnectionFactory::makeConnection();




$app->run();
