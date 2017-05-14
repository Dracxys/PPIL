<?php
namespace  PPIL\controlers;

use PPIL\models\Enseignant;
use PPIL\views\VueHome;
use PPIL\views\VueUtilisateur;


use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use Slim\Slim;

class HomeControler{

    public function accueil() {
        if(isset($_SESSION['mail'])){
            $v = new VueUtilisateur();
            echo $v->home();
        }else {
            $v  = new VueHome();
            echo $v->home(0);
        }
    }

    public function connection() {
        $val = Slim::getInstance()->request->post();
        $email = filter_var($val['email'], FILTER_SANITIZE_EMAIL);
        $pass = filter_var($val['password'], FILTER_SANITIZE_STRING);

        $u = Enseignant::where("mail", "like", $email)->first();
        if($u != null){
            $hash = $u->mdp;
            if (password_verify($pass, $hash)) {
                $_SESSION['mail'] = $u->mail;
                $_SESSION['nom'] = $u->nom;
                $_SESSION['prenom'] = $u->prenom;
                $v = new VueUtilisateur();
                echo $v->home();
            } else {
                $v = new VueHome();
                echo $v->home(1);
            }
        }else{
            $v = new VueHome();
            echo $v->home(1);
        }


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


    }

    public function inscription(){
        if(isset($_SESSION['mail'])){
            $v = new VueUtilisateur();
            echo $v->home();
        }else {
            $v  = new VueHome();
            echo $v->inscription();
        }
    }


}