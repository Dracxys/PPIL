<?php


session_start();
require 'vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \PPIL\controlers\HomeControler as HomeControler;

$app = new \Slim\App;

\PPIL\utils\ConnectionFactory::setConfig('db.ppil.conf.ini');
\PPIL\utils\ConnectionFactory::makeConnection();

$app->get('/', function (Request $request, Response $response) {
    $ctest = new HomeControler();
    $ctest->accueil();
});

$app->run();
