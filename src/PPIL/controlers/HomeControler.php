<?php
namespace  PPIL\controlers;

use PPIL\views\AbstractView as AbstractView;
use PPIL\views\VueHome;
use PPIL\views\VueUtilisateur;


use PPIL\models\Notification;
use PPIL\models\NotificationInscription;

class HomeControler{

    public function accueil() {
        $v  = new VueHome();
        echo $v->home();
    }

    public function connection() {
        #TODO



        #si tout se passe bien
        /*
        $n = new Notification();
        $n->message = "plop";
        $n->besoin_validation = 1;
        $n->validation = 0;
        $n->type_notification = 'PPIL\models\NotificationInscription';
        $n->save();


        $n2 = new NotificationInscription();
        $n2->nom = "c";
        $n2->prenom="c";
        $n2->statut="Enseignant-chercheur permanent";
        $n2->mail = "c";
        $n2->mot_de_passe = "o";
        $n2->save();
        $n2->notification()->save($n);
        */

        $v  = new VueUtilisateur();
        echo $v->home();
    }


}