<?php
namespace  PPIL\controlers;

use PPIL\models\Enseignant;
use PPIL\views\VueHome;
use PPIL\views\VueModifProfil;
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
                $v = new VueModifProfil();
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

    public function oubliMDP(){
        $v  = new VueHome();
        echo $v->oubliMDP();
    }

    public function changementMDP(){
        $val = Slim::getInstance()->request->post();
        $email = filter_var($val['email'], FILTER_SANITIZE_EMAIL);

        $e = Enseignant::where('mail','like',$email)->first();
        if(!empty($e)){
            $mail = new MailControler();
            $corps = "Voici un lien pour réinitialiser votre mot de passe.\n ";
            $lien = $_SERVER['HTTP_HOST'] . Slim::getInstance()->request->getRootUri();
            $lien = 'http://'.$lien.'/oublieMDP/suppression/'.$e->rand;
            $corps = $corps . $lien;
            $mail->sendMaid($e->mail,'Réinitialisation du mot de passe', $corps);
            $v = new VueHome();
            echo $v->oubliMDP(2);
        }else{
            $v = new VueHome();
            echo $v->oubliMDP(1);
        }
    }

    public function changementMDPForm($id){
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $e = Enseignant::where('rand','=',$id)->first();
        if(!empty($e)){
            $v = new VueHome();
            echo $v->changementMDP($e);
        }else{
            $v = new VueHome();
            echo $v->home(0);
        }
    }

    public function changeMDP(){
        $val = Slim::getInstance()->request->post();
        $pass = filter_var($val['password'], FILTER_SANITIZE_EMAIL);
        $pass2 = filter_var($val['password2'], FILTER_SANITIZE_EMAIL);
        $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);

        $e = Enseignant::where('rand','=',$id)->first();
        if(!empty($e)){
            if($pass == $pass2){
                Enseignant::reinitialiserMDP($e,password_hash($pass,PASSWORD_DEFAULT));
                $v = new VueHome();
                echo $v->oubliMDPErreur(0);
            }else{
                $v = new VueHome();
                echo $v->changementMDP($e,1);
            }
        }else{
            $v = new VueHome();
            echo $v->oubliMDPErreur(1);
        }

    }


}