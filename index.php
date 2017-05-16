<?php


session_start();
require 'vendor/autoload.php';


use \PPIL\controlers\HomeControler as HomeControler;
use PPIL\controlers\UtilisateurControler;
use PPIL\controlers\ModifProfilControler;

$app = new \Slim\Slim();

\PPIL\utils\ConnectionFactory::setConfig('db.ppil.conf.ini');
\PPIL\utils\ConnectionFactory::makeConnection();

$app->get('/', function () {
    $c = new HomeControler();
    $c->accueil();
})->name('home');

$app->get('/test', function () use ($app){

});

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

$app->get('/inscription', function (){
    $c = new HomeControler();
    $c->inscription();
});

$app->post('/home/inscription', function (){
    $c = new UtilisateurControler();
    $c->inscription();
})->name('validerInscription');

$app->get('/home/deconnexion', function (){
    $c = new UtilisateurControler();
    $c->deconnexion();
})->name('deconnexion');

$app->post('/oubliMDP', function (){
    $c = new HomeControler();
    $c->oubliMDP();
})->name('oubliMDP');

$app->get('/oubliMDP', function (){
    $c = new HomeControler();
    $c->oubliMDP();
});

$app->post('/oublieMDP/validation',function (){
    $c = new HomeControler();
    $c->changementMDP();
})->name('changementMDP');

$app->get('/oublieMDP/suppression/:id', function ($id){
    $c = new HomeControler();
    $c->changementMDPForm($id);
});

$app->post('/oublieMDP/nouveau', function (){
    $c = new HomeControler();
    $c->changeMDP();
})->name('changementMDPValider');

$app->post("/home/modificationProfil", function (){
    $c = new ModifProfilControler();
    $c->modificationProfil();
})->name('modificationProfil');

$app->post("/home/modifPassword", function (){
    $c = new ModifProfilControler();
    $c->modificationPassword();
})->name('modificationPassword');

$app->post("/home/modificationPhoto", function (){
    $c = new ModifProfilControler();
    $c->modifPhoto();
})->name('modificationPhoto');


$app->post('/home/formation/ue/',function () use ($app){
    $c = new \PPIL\controlers\FormationControler();
    $c->infoForm();
})->name('infoForm');


/* ===================== liens dans la barre de navigation ==================*/
$app->get('/home/profil', function (){
    $c = new ModifProfilControler();
    $c->home();
})->name('profilUtilisateur');

$app->get('/home/enseignement', function (){
    $c = new UtilisateurControler();
    $c->enseignement();
})->name('enseignementUtilisateur');

$app->get('/home/ue', function (){
    $c = new UtilisateurControler();
    $c->ue();
})->name('ueUtilisateur');

$app->get('/home/formation', function (){
    $c = new \PPIL\controlers\FormationControler();
    $c->home();
})->name('formationUtilisateur');

$app->get('/home/enseignants', function (){
    $c = new UtilisateurControler();
    $c->enseignants();
})->name('enseignantsUtilisateur');

$app->get('/home/journal', function (){
    $c = new UtilisateurControler();
    $c->journal();
})->name('journalUtilisateur');

$app->post('/home/journal/actionNotification', function (){
    $c = new UtilisateurControler();
    $c->journal_action_notification();
})->name('JournalUtilisateur.actionNotification');

$app->get('/home/annuaire', function (){
    $c = new UtilisateurControler();
    $c->annuaire();
})->name('annuaireUtilisateur');
/* ===================== liens dans la barre de navigation ==================*/

$app->run();
