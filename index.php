<?php


session_start();
require 'vendor/autoload.php';


use \PPIL\controlers\HomeControler as HomeControler;
use PPIL\controlers\UtilisateurControler;
use PPIL\models\Enseignant;

$app = new \Slim\Slim();

\PPIL\utils\ConnectionFactory::setConfig('db.ppil.conf.ini');
\PPIL\utils\ConnectionFactory::makeConnection();

$app->get('/', function () {
    $c = new HomeControler();
    $c->accueil();
})->name('home');

$app->post('/',function (){
    $c = new HomeControler();
    $c->accueil();
});


$app->post('/login', function () use ($app){
    $c = new HomeControler();
    $c->connection();
})->name('login');

$app->get('/home', function (){
    $c = new UtilisateurControler();
    $c->home();
})->name('homeUtilisateur');


$app->post('/inscription', function (){
    $c = new HomeControler();
    $c->inscription();
})->name('inscription');

$app->run();
