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
            $user->nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
            $user->prenom = filter_var($val['prenom'], FILTER_SANITIZE_STRING);
            $user->mail = filter_var($val['email'], FILTER_SANITIZE_EMAIL);
            $user->statut = filter_var($val['statut'], FILTER_SANITIZE_STRING);
            switch (filter_var($val['statut'], FILTER_SANITIZE_STRING)){
                case "Professeur des universités" :
                    $user->volumeMin = 192;
                    $user->volumeMax = 384;
                    break;
                case "Maître de conférences" :
                    $user->volumeMin = 192;
                    $user->volumeMax = 384;
                    break;
                case "PRAG" :
                    $user->volumeMin = 384;
                    $user->volumeMax = 768;
                    break;
                case "ATER" :
                    $user->volumeMin = 192;
                    $user->volumeMax = 192;
                    break;
                case "1/2 ATER" :
                    $user->volumeMin = 96;
                    $user->volumeMax = 96;
                    break;
                case "Doctorant" :
                    $user->volumeMin = 64;
                    $user->volumeMax = 64;
                    break;
                case "Vacataire" :
                    $user->volumeMin = 0;
                    $user->volumeMax = 96;
                    break;
            }
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
            if(password_verify($val['ancien'],$user->mdp)){
                $nouv = filter_var($val['nouv'], FILTER_SANITIZE_STRING);
                $conf = filter_var($val['conf'], FILTER_SANITIZE_STRING);
                if($nouv == $conf){
                    $user->mdp = password_hash($conf,PASSWORD_DEFAULT);
                    $user->save();
                    echo $v->home($user,1);
                } else echo $v->home($user,3);
            }else echo $v->home($user,2);
        }
    }
}