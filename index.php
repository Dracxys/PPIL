<?php


session_start();
require 'vendor/autoload.php';


use \PPIL\controlers\HomeControler as HomeControler;

$app = new \Slim\Slim();

\PPIL\utils\ConnectionFactory::setConfig('db.ppil.conf.ini');
\PPIL\utils\ConnectionFactory::makeConnection();

$app->get('/', function () {
    $ctest = new HomeControler();
    $ctest->accueil();
});


$app->post('/login', function (){
    echo "vide";
})->name('login');

$app->run();
