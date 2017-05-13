<?php


session_start();
require 'vendor/autoload.php';


use \PPIL\controlers\HomeControler as HomeControler;

$app = new \Slim\Slim();

\PPIL\utils\ConnectionFactory::setConfig('db.ppil.conf.ini');
\PPIL\utils\ConnectionFactory::makeConnection();

$app->get('/', function () {
    $c = new HomeControler();
    $c->accueil();
});


$app->post('/login', function (){
    $c = new HomeControler();
    $c->connection();
})->name('login');

$app->run();
