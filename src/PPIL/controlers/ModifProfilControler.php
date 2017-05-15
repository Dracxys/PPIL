<?php
namespace  PPIL\controlers;

use PPIL\models\Enseignant;
use PPIL\views\VueHome;
use PPIL\views\VueUtilisateur;
use PPIL\views\VueModifProfil;


use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use Slim\Slim;

class ModifProfilControler{

    public function home() {
            $user = Enseignant::where("mail", "like",$_SESSION['mail'])->first();
            $v = new VueModifProfil();
            echo $v->home($user, -1);
    }

    public function modificationProfil(){
        if(isset($_SESSION['mail'])){
            $val = Slim::getInstance()->request->post();
            $user = Enseignant::where("mail", "like",$_SESSION['mail'])->first();
            $user->nom = $val['nom'];
            $user->prenom = $val['prenom'];
            $user->mail = $val['email'];
            $user->statut = $val['statut'];
            $user->save();

            $v = new VueModifProfil();
            echo $v->home($user,0);
        }
    }

    public function modificationPassword(){
        if(isset($_SESSION['mail'])){
            $v = new VueModifProfil();
            $val = Slim::getInstance()->request->post();
            $user = Enseignant::where("mail", "like",$_SESSION['mail'])->first();
            if($val['ancien'] == $user->mdp){
                if($val['nouv'] == $val['conf']){
                    $user->mdp = $val['conf'];
                    $user->save();
                    echo $v->home($user,1);
                } else echo $v->home($user,3);
            }else echo $v->home($user,2);
        }
    }
}