<?php


session_start();
require 'vendor/autoload.php';


use \PPIL\controlers\HomeControler as HomeControler;
use PPIL\controlers\UtilisateurControler;
use PPIL\controlers\ModifProfilControler;
use PPIL\controlers\FormationControler;
use PPIL\controlers\UEControler;
use PPIL\controlers\EnseignantsControler;

use PPIL\models\Intervention;
use League\Csv\Writer;
use League\Csv\Reader;
use Slim\Slim;

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

$app->get('/test',function (){
    $intervention = Intervention::all();
    $csv = Writer::createFromFileObject(new SplTempFileObject());
    $csv->setDelimiter(';');
    $csv->insertOne($intervention->first()->getTableColumns());
    foreach($intervention as $i){
        $csv->insertOne($i->toArray());
    }
    $csv->output('interventions.csv');
});


$app->get('/test2',function (){
    $csv = Reader::createFromPath(Slim::getInstance()->root() . 'interventions.csv');
    $csv->setOffset(1);
    $nb_insert = $csv->each(function ($row) {
        $result = false;
        $i = Intervention::find($row[0]);
        if(is_null($i)){
            $intervention = new Intervention();
            $intervention->id_intervention = $row[0];
            $intervention->fst = $row[1];
            $intervention->heuresCM = $row[2];
            $intervention->heuresTP = $row[3];
            $intervention->heuresTD = $row[4];
            $intervention->heuresEI = $row[5];
            $intervention->groupeTP = $row[6];
            $intervention->groupeTD = $row[7];
            $intervention->groupeEI = $row[8];
            $intervention->mail_enseignant = $row[9];
            $intervention->id_UE = $row[10];
            $intervention->save();
            $result = true;
        }
        return $result;

    });
    //$csv->output('interventions.csv');
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


$app->post("/home/modifResponsabilite",function(){
    $c = new ModifProfilControler();
    $c->modifRespo();
})->name('modificationResponsabilite');

/* ===================== liens dans formation ==================*/

$app->post('/home/formation/ue/',function () use ($app){
    $c = new FormationControler();
    $c->infoForm();
})->name('infoForm');

$app->post('/home/formation/ue/infos', function (){
    $c = new FormationControler();
    $c->infoUE();
});

$app->post('/home/formation/ue/total', function (){
    $c = new FormationControler();
    $c->total();
});

$app->post('/home/formation/ue/modif', function (){
    $c = new FormationControler();
    $c->modifForm();
});

$app->post('/home/formation/ue/creer/form',function (){
    $c = new FormationControler();
    $c->creerForm();
});

$app->post('/home/formation/ue/supprimer',function (){
   $c = new FormationControler();
   $c->supprimerUE();
});

$app->get('/home/formation/ue/enseignant', function (){
    $c = new FormationControler();
    $c->recupererEnseignant();
});

$app->post('/home/formation/ue/ajout/ue',function (){
    $c = new FormationControler();
    $c->ajouterUE();
});

$app->post('/home/formation/ue/actu',function (){
    $c = new FormationControler();
    $c->actualisation();
});

$app->post('/home/formation/supprimer',function (){
    $c = new FormationControler();
    $c->supprimerForm();
});

$app->post('/home/formation/info',function (){
    $c = new FormationControler();
    $c->form();
});

$app->post('/home/formation/ue/modif/form',function (){
    $c = new FormationControler();
    $c->modifierForm();
});

/* ===================== liens dans formation ==================*/

/* ===================== liens dans la barre de navigation ==================*/
$app->get('/home/profil', function (){
    $c = new ModifProfilControler();
    $c->home();
})->name('profilUtilisateur');

$app->get('/home/enseignement', function (){
    $c = new UtilisateurControler();
    $c->enseignement();
})->name('enseignementUtilisateur');

$app->post('/home/enseignement/actionEnseignement', function (){
    $c = new UtilisateurControler();
    $c->enseignement_action();
})->name('enseignementUtilisateur.actionEnseignement');

$app->post('/home/enseignement/actionEnseignementAjouter', function (){
    $c = new UtilisateurControler();
    $c->enseignement_action_ajouter();
})->name('enseignementUtilisateur.actionEnseignementAjouter');

$app->post('/home/enseignement/actionEnseignementAjouterAutre', function (){
    $c = new UtilisateurControler();
    $c->enseignement_action_ajouter_autre();
})->name('enseignementUtilisateur.actionEnseignementAjouterAutre');

$app->get('/home/ue', function (){
    $c = new UEControler();
    $c->home();
})->name('ueUtilisateur');

$app->post('/home/ue/compoUE', function (){
    $c = new UEControler();
    $c->infoUE();
})->name('compoUE');

$app->post('/home/ue/compoUE/modif', function (){
    $c = new UEControler();
    $c->modifierUE();
});

$app->post('/home/ue/compoUE/listIntervenant', function (){
    $c = new UEControler();
    $c->intervenantsUE();
});

$app->post('/home/ue/compoUE/boutonModif', function (){
    $c = new UEControler();
    $c->boutonModif();
});

$app->post('/home/ue/compoUE/modifHeureEnseignant', function (){
    $c = new UEControler();
    $c->modifierHeureEnseignant();
});

$app->post('/home/ue/compoUE/suppressionEnseignant', function (){
    $c = new UEControler();
    $c->supprimerEnseignant();
});

$app->get('/home/formation', function (){
    $c = new FormationControler();
    $c->home();
})->name('formationUtilisateur');

$app->get('/home/enseignants', function (){
    $c = new EnseignantsControler();
    $c->home();
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

/* ===================== liens dans enseignants ==================*/

$app->get('/home/enseignants/vueinscriptionDI', function (){
    $c = new EnseignantsControler();
    $c->lancerVueInscriptionParDI();
});

$app->post('/home/enseignants/vueinscriptionDI', function (){
    $c = new EnseignantsControler();
    $c->lancerVueInscriptionParDI();
})->name('vueinscriptionDI');

$app->get('/home/enseignants/inscriptionParDI', function (){
    $c = new EnseignantsControler();
    $c->inscriptionParDI();
})->name('inscriptionParDI');

$app->post('/home/enseignants/inscriptionParDI', function (){
    $c = new EnseignantsControler();
    $c->inscriptionParDI();
});

$app->post('/home/enseignants', function (){
    $c = new EnseignantsControler();
    $c->home();
});

$app->get('/home/enseignants/supprimer/:id',function ($id){
    $c = new EnseignantsControler();
    $c->supprimer($id);
})->name('supprimerEnseignant');

$app->get('/home/enseignants/profilEnseignant/:id', function ($id){
    $c = new EnseignantsControler();
    $c->profilEnseignant($id);
})->name('profilEnseignant');

/* ===================== liens dans enseignants ==================*/

$app->run();
