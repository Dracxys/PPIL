<?php
namespace  PPIL\controlers;

use PPIL\models\Enseignant;
use PPIL\models\Formation;
use PPIL\models\Intervention;
use PPIL\models\NotificationResponsabilite;
use PPIL\models\Responsabilite;
use PPIL\models\UE;
use PPIL\views\VueHome;
use PPIL\views\VueUtilisateur;
use PPIL\views\VueModifProfil;


use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use Slim\Slim;

class ModifProfilControler
{

    public function home()
    {
        if(isset($_SESSION['mail'])){
            $user = Enseignant::where("mail", "like", $_SESSION['mail'])->first();
            $v = new VueModifProfil();
            echo $v->home($user, -1);
        }else{
            $v = new VueHome();
            echo $v->home(0);
        }

    }

    public function modificationProfil()
    {
        if (isset($_SESSION['mail'])) {
            $val = Slim::getInstance()->request->post();
            $ancien = Enseignant::where("mail", "like", $_SESSION['mail'])->first();
            if($_SESSION['mail'] != filter_var($val['email'], FILTER_SANITIZE_EMAIL)){
                $tmp = Enseignant::find(filter_var($val['email'], FILTER_SANITIZE_EMAIL));
                if(empty($tmp)){
                    $user = new Enseignant();
                    $user->nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
                    $user->prenom = filter_var($val['prenom'], FILTER_SANITIZE_STRING);
                    $user->mail = filter_var($val['email'], FILTER_SANITIZE_EMAIL);
                    $user->statut = filter_var($val['statut'], FILTER_SANITIZE_STRING);
                    $user->mdp = $ancien->mdp;
                    $user->rand = $ancien->rand;
                    $user->photo = $ancien->photo;
                    $user->volumeCourant = $ancien->volumeCourant;
                    switch (filter_var($val['statut'], FILTER_SANITIZE_STRING)) {
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
                    $inter = Intervention::where('mail_enseignant','like',$_SESSION['mail'])->get();
                    foreach ($inter as $value){
                        $value->mail_enseignant = filter_var($val['email'], FILTER_SANITIZE_EMAIL);
                        $value->save();
                    }
                    $resp = Responsabilite::where('enseignant','like',$_SESSION['mail'])->get();
                    foreach ($resp as $value){
                        $value->enseignant = filter_var($val['email'], FILTER_SANITIZE_EMAIL);
                        $value->save();
                    }
                    $notif = Notification::where('mail_destinataire','like',$_SESSION['mail'])->get();
                    foreach ($notif as $value){
                        $value->mail_destinataire = filter_var($val['email'], FILTER_SANITIZE_EMAIL);
                        $value->save();
                    }
                    $notif = Notification::where('mail_source','like',$_SESSION['mail'])->get();
                    foreach ($notif as $value){
                        $value->mail_source = filter_var($val['email'], FILTER_SANITIZE_EMAIL);
                        $value->save();
                    }
                    $ancien->delete();
                    $_SESSION['mail'] = $user->mail;
                    $v = new VueModifProfil();
                    echo $v->home($user, 0);
                }else{
                    $v = new VueModifProfil();
                    echo $v->home($ancien, 0);
                }
            }else{
                $ancien->nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
                $ancien->prenom = filter_var($val['prenom'], FILTER_SANITIZE_STRING);
                $ancien->statut = filter_var($val['statut'], FILTER_SANITIZE_STRING);
                switch (filter_var($val['statut'], FILTER_SANITIZE_STRING)) {
                    case "Professeur des universités" :
                        $ancien->volumeMin = 192;
                        $ancien->volumeMax = 384;
                        break;
                    case "Maître de conférences" :
                        $ancien->volumeMin = 192;
                        $ancien->volumeMax = 384;
                        break;
                    case "PRAG" :
                        $ancien->volumeMin = 384;
                        $ancien->volumeMax = 768;
                        break;
                    case "ATER" :
                        $ancien->volumeMin = 192;
                        $ancien->volumeMax = 192;
                        break;
                    case "1/2 ATER" :
                        $ancien->volumeMin = 96;
                        $ancien->volumeMax = 96;
                        break;
                    case "Doctorant" :
                        $ancien->volumeMin = 64;
                        $ancien->volumeMax = 64;
                        break;
                    case "Vacataire" :
                        $ancien->volumeMin = 0;
                        $ancien->volumeMax = 96;
                        break;
                }
                $ancien->save();
                $v = new VueModifProfil();
                echo $v->home($ancien, 0);
            }

        }
    }

    public function modificationPassword()
    {
        if (isset($_SESSION['mail'])) {
            $v = new VueModifProfil();
            $val = Slim::getInstance()->request->post();
            $user = Enseignant::where("mail", "like", $_SESSION['mail'])->first();
            if (password_verify($val['ancien'], $user->mdp)) {
                $nouv = filter_var($val['nouv'], FILTER_SANITIZE_STRING);
                $conf = filter_var($val['conf'], FILTER_SANITIZE_STRING);
                if ($nouv == $conf) {
                    $user->mdp = password_hash($conf, PASSWORD_DEFAULT);
                    $user->save();
                    echo $v->home($user, 1);
                } else echo $v->home($user, 3);
            } else echo $v->home($user, 2);
        }
    }



    public function modifRespo(){
        if(isset($_SESSION['mail'])){
            $error = true;
            $val = Slim::getInstance()->request->post();
            $user = Enseignant::where("mail","like",$_SESSION['mail'])->first();

            $notif = new NotificationResponsabilite();
            $n = new Notification();
            if(isset($val['ueSelect'])){
                $type = UE::where("nom_UE","like",$val['ueSelect'])->first();

                $intitule="Responsable UE";
                $id_UE = $type->id_UE;
                $notif->intitule=$intitule;
                $notif->privilege = 0;
                $notif->id_UE = $id_UE;
                $n->message = "Demande de responsabilité : UE " + $val['ueSelect'];
                $error = false;
            }
            if(isset($val['formSelect'])) {
                $type = Formation::where("nomFormation", "like", $val['formSelect'])->first();
                $intitule = "Responsable Formation";
                $id_formation = $type->id_formation;
                $notif->intitule = $intitule;
                $notif->privilege = 1;
                $notif->id_formation = $id_formation;
                $n->message = "Demande de responsabilité : formation " + $val['formSelect'];
                $error = false;
            }
            $n->besoin_validation = 1;
            $n->validation = 0;
            $n->type_notification = 'PPIL\models\NotificationResponsabilite';

            $resp = Responsabilite::where('intituleResp', '=', 'Responsable du departement informatique')->first();
            $ens_respDI = Enseignant::where('mail', '=', $resp->enseignant)->first();
            $n->mail_destinataire = $ens_respDI->mail;
            $n->mail_source = $_SESSION['mail'];
            $n->save();
            $notif->id_notification = $n->id_notification;

            $notif->save();

            $v = new VueModifProfil();
            echo $v->home($user,8);
        }
    }

    public function modifPhoto()
    {
        if (isset($_SESSION['mail'])) {

            $user = Enseignant::where("mail", "like", $_SESSION['mail'])->first();
            if ($_FILES['file']['size'] > 0) {
                $chemin = "assets/images/profil_pictures/";
                $root = Slim::getInstance()->root() . "assets/images/profil_pictures/";
                $num = 7;
                $extensions_valides = array('jpg', 'jpeg', 'gif', 'png');
                $maxsize = 20971520;
                $extension_upload = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
                $namePic = md5(uniqid(rand(), true));
                $erreur = null;
                if ($_FILES['file']['error'] > 0) {
                    $num = 4;
                } else if ($_FILES['file']['size'] > $maxsize) {
                    $num = 5;
                } else if (!in_array($extension_upload, $extensions_valides)) {
                    $num = 6;
                } else {
                    move_uploaded_file($_FILES['file']['tmp_name'],  $root . $namePic . "." . $extension_upload);
                    if ($user->photo != null) {
                        $nom = Slim::getInstance()->root(). $user->photo;
                        unlink($nom);
                    }
                    $user->photo = $chemin . $namePic . "." . $extension_upload;
                    $user->save();
                }
            }
            $v = new VueModifProfil();
            echo $v->home($user, $num);
        }
    }
}