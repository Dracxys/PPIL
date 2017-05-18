<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 16/05/2017
 * Time: 19:35
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\models\Formation;
use PPIL\models\Intervention;
use PPIL\models\Responsabilite;
use PPIL\views\VueUe;
use PPIL\views\VueHome;
use PPIL\views\VueModifProfil;
use PPIL\views\VueUtilisateur;
use PPIL\models\UE;
use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use Slim\Slim;


class UEControler
{
    public function home(){
        if (isset($_SESSION['mail'])) {
            $e = Enseignant::find($_SESSION['mail']);
            $respon = Responsabilite::where('enseignant','like', $e->mail)->first();
            if(!empty($respon)){
                $privi = Enseignant::get_privilege($e);
                if ($privi == 2) {
                    $ue = UE::where('fst', 'like', 1)->get();
                    $res = array();
                    foreach ($ue as $value) {
                        if (!in_array($value, $res)) {
                            $res[] = $value;
                        }
                    }
                    $v = new VueUe();
                    echo $v->home($res);
                } elseif ($privi == 1) {
                    $resp = Responsabilite::where('enseignant', 'like', $e->mail)->get();
                    $res = array();
                    foreach ($resp as $value) {
                        $ue = UE::where('id_formation', 'like', $value->id_formation)->first();
                        if (!in_array($ue, $res)) {
                            $res[] = $ue;
                        }
                    }
                    $intervention = Intervention::where('mail_enseignant', 'like', $e->mail)->get();
                    foreach ($intervention as $value){
                        $ue = UE::where('id_UE', 'like', $value->id_UE)->where('fst', 'like', 1)->first();
                        if(!in_array($ue, $res)){
                            $res[] = $ue;
                        }
                    }
                    $v = new VueUe();
                    echo $v->home($res);
                } else {
                    $resp = Responsabilite::where('enseignant', 'like', $e->mail)->first();
                    $res = array();
                    $ue = UE::where('id_UE', 'like', $resp->id_UE)->first();
                       if (!in_array($ue, $res)) {
                           $res[] = $ue;
                       }
                    $intervention = Intervention::where('mail_enseignant', 'like', $e->mail)->get();
                    foreach ($intervention as $value){
                        $ue = UE::where('id_UE', 'like', $value->id_UE)->where('fst', 'like', 1)->first();
                        if(!in_array($ue, $res)){
                            $res[] = $ue;
                        }
                    }
                    $v = new VueUe();
                    echo $v->home($res);
                }
            }else{
                $res = array();
                $intervention = Intervention::where('mail_enseignant', 'like', $e->mail)->get();
                foreach ($intervention as $value){
                    $ue = UE::where('id_UE', 'like', $value->id_UE)->where('fst', 'like', 1)->first();
                    if(!in_array($ue, $res)){
                        $res[] = $ue;
                    }
                }
                $v = new VueUe();
                echo $v->home($res);
            }

        } else {
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }

    }

    public function infoUE(){
        $app = Slim::getInstance();
        $val = $app->request->post();
        $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);
        $ue = UE::where('id_UE', '=', $id)->first();
        $app->response->headers->set('Content-Type', 'application/json');
        if (empty($ue)) {
            $res = array();
            echo json_encode($res);
        } else {
            echo json_encode($ue);
        }
    }

    public function intervenantsUE() {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $app->response->headers->set('Content-Type', 'application/json');
        $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);
        $interventions = Intervention::where('id_UE', 'like', $id)->get();
        $res = array();
        if(empty($interventions)){
            echo json_encode($res);
        }else{
            foreach ($interventions as $value){
                $user = Enseignant::where('mail', 'like', 'mail_enseignant')->first();
                if(!in_array($user, $res)){
                    $res = $user;
                }
            }
            echo json_encode($res);
        }
    }

    public function creerUE(){
        if(isset($_SESSION['mail'])) {
            $val = Slim::getInstance()->request->post();
            $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);

            $heuresCM = filter_var($val['heuresCM'], FILTER_SANITIZE_STRING);
            $heuresTP = filter_var($val['heuresTP'], FILTER_SANITIZE_STRING);
            $heuresTD = filter_var($val['heuresTD'], FILTER_SANITIZE_STRING);
            $heuresEI = filter_var($val['heuresEI'], FILTER_SANITIZE_STRING);

            $groupeTP = filter_var($val['groupeTP'], FILTER_SANITIZE_STRING);
            $groupeTD = filter_var($val['groupeTD'], FILTER_SANITIZE_STRING);
            $groupeEI = filter_var($val['groupeEI'], FILTER_SANITIZE_STRING);

            $nom_responsable = filter_var($val['nom_responsable'], FILTER_SANITIZE_STRING);

            UE::creerUE($nom, $heuresCM, $heuresTP, $heuresTD, $heuresEI, $groupeTP, $groupeTD, $groupeEI);

            if (!empty($nom_responsable)){
                $responsable = Enseignant::where('nom', 'like', $nom_responsable)->first();
                if(empty(responsable)){
                    // *************************** ERREUR : L'ENSIGNANT N'EXISTE PAS
                } else {
                    $nouvUE = UE::where('nom_UE', 'like', $nom)->first();
                    ajoutResponsabilite($responsable->mail, 'responsable ue', null, $nouvUE->id_UE);
                }
            }

        }
    }
	
	public function modifierUE(){
        $app = Slim::getInstance();
        $val = $app->request->post();
        $id = filter_var($val['id'],FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);
        if (self::verif($val)) {
            $ue = UE::where('id_UE','=',$id)->first();
            if (!empty($ue)) {
                $heuresCM = filter_var($val['heureCM'],FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);;
                $heuresTD = filter_var($val['heureTD'],FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);
                $heuresTP = filter_var($val['heureTP'],FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);
                $heuresEI = filter_var($val['heureEI'],FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);
                $groupeTD = filter_var($val['nbGroupeTD'],FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);
                $groupeTP = filter_var($val['nbGroupeTP'],FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);
                $groupeEI = filter_var($val['nbGroupeEI'],FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);
                UE::modifierUE($ue->id_UE,$ue->nom_UE,$heuresCM,$heuresTP,$heuresTD,$heuresEI,$groupeTP,$groupeTD,$groupeEI);
                $resp = UE::getResponsable($ue->id_UE);
                $mail = new MailControler();
                $e = Enseignant::find($_SESSION['mail']);
                $tab = array();
                foreach ($resp as $value){
                    $mes = "Responsable : " . $e->prenom . " " . $e->nom . ".\n";
                    $mes .= "Les heures attendus de l UE " . $ue->nom_UE . " ont été modifié.";
                    $mail->sendMaid($value->enseignant,"Modification des heures attendus de votre UE", $mes);
                    $tab[] = $value->enseignant;
                }
                $ens = Intervention::where('id_UE','=',$ue->id_UE)->get();
                foreach ($ens as $value){
                    if(!in_array($value->mail_enseignant,$tab)){
                        $mes = "Responsable : " . $e->prenom . " " . $e->nom . ".\n";
                        $mes .= "Les heures attendus de l UE " . $ue->nom_UE . " ont été modifié.";
                        $mail->sendMaid($value->mail_enseignant,"Modification des heures attendus d'un UE", $mes);
                    }
                }

                $app->response->headers->set('Content-Type', 'application/json');
                $res = array();
                $res[] = 'true';
                echo json_encode($res);

            } else {
                $app->response->headers->set('Content-Type', 'application/json');
                $res = array();
                $res[] = 'ue existe pas';
                echo json_encode($res);

            }
        } else {
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res[] = 'verif fausse';
            echo json_encode($res);

        }
	}

    private function verif($val)
    {
        $res = false;
        if ($val['heureCM'] >= 0 && $val['nbGroupeTD'] >= 0 && $val['heureTD'] >= 0 && $val['nbGroupeTP'] >= 0 &&
            $val['heureTP'] >= 0 && $val['nbGroupeEI'] >= 0 && $val['heureEI'] >= 0
        ){
            $res = true;
        }
        return $res;
    }
	
}