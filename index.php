<?php


session_start();
require 'vendor/autoload.php';


use \PPIL\controlers\HomeControler as HomeControler;

$app = new \Slim\Slim();

\PPIL\utils\ConnectionFactory::setConfig('db.ppil.conf.ini');
\PPIL\utils\ConnectionFactory::makeConnection();

$app->get('/', function () use ($app) {
    $c = new HomeControler();
    $c->accueil();
});


$app->post('/login', function () use ($app){
    $request = $app->request();
    $email = $request->post('email');
    echo $email;
    //    $c = new HomeControler();
    //$c->connection();
})->name('login');

$app->run();
