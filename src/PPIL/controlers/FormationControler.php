<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 16/05/2017
 * Time: 11:27
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\models\Formation;
use PPIL\models\Intervention;
use PPIL\models\Responsabilite;
use PPIL\models\UE;
use PPIL\views\VueFormation;
use PPIL\views\VueHome;
use Slim\Slim;

class FormationControler
{
    public function home()
    {
        if (isset($_SESSION['mail'])) {
            $e = Enseignant::find($_SESSION['mail']);
            $privi = Enseignant::get_privilege($e);
            if ($privi == 2) {
                $f = Formation::where('fst','=','1')->get();
                $val = array();
                foreach ($f as $value) {
                    if (!in_array($value->nomFormation, $val)) {
                        $val[] = $value->nomFormation;
                    }
                }
                $val[] = 'DI';
                $v = new VueFormation();
                echo $v->home($val);
            } elseif ($privi == 1) {
                $resp = Responsabilite::where('enseignant', 'like', $e->mail)->get();
                $res = array();
                foreach ($resp as $value) {
                    $f = Formation::find($value->id_formation);
                    if (!in_array($f->nomFormation, $res)) {
                        $res[] = $f->nomFormation;
                    }
                }
                $v = new VueFormation();
                echo $v->home($res);
            } else {
                Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
            }


        } else {
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }

    }

    public function infoForm()
    {
        $app = Slim::getInstance();
        $nom = $app->request->post();
        $nom = filter_var($nom['nom'], FILTER_SANITIZE_STRING);
        $for = \PPIL\models\Formation::where('nomFormation', 'like', $nom)->first();
        $res = array();
        if (!empty($for)) {
            $ue = UE::where('id_formation', '=', $for->id_formation)->get();
            if (!empty($ue)) {
                foreach ($ue as $value) {
                    $res[] = $value->id_UE;
                    $res[] = $value->nom_UE;
                }
            }
        }
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($res);
    }

    public function infoUE()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $id = filter_var($val['id'], FILTER_SANITIZE_STRING);
        $ue = UE::where('id_UE', '=', $id)->first();
        $app->response->headers->set('Content-Type', 'application/json');
        if (empty($ue)) {
            $res = array();
            echo json_encode($res);
        } else {
            echo json_encode($ue);
        }

    }

    public function total()
    {
        $app = Slim::getInstance();
        $nom = $app->request->post();
        $nom = filter_var($nom['nom'], FILTER_SANITIZE_STRING);
        $for = \PPIL\models\Formation::where('nomFormation', 'like', $nom)->first();
        $res = array();
        if (!empty($for)) {
            $ue = UE::where('id_formation', '=', $for->id_formation)->get();
            if (!empty($ue)) {
                $cmPrev = 0;
                $cm = 0;
                $tdPrev = 0;
                $td = 0;
                $tpPrev = 0;
                $tp = 0;
                $eiPrev = 0;
                $ei = 0;
                foreach ($ue as $value) {
                    $cmPrev = $cmPrev + $value->prevision_heuresCM;
                    $cm = $cm + $value->heuresCM;
                    $tdPrev = $tdPrev + $value->prevision_heuresTD;
                    $td = $td + $value->heuresTD;
                    $tpPrev = $tpPrev + $value->prevision_heuresTP;
                    $tp = $tp + $value->heuresTP;
                    $eiPrev = $eiPrev + $value->prevision_heuresEI;
                    $ei = $ei + $value->heuresEI;
                }
                $res[] = $cmPrev;
                $res[] = $cm;
                $res[] = $tdPrev;
                $res[] = $td;
                $res[] = $tpPrev;
                $res[] = $tp;
                $res[] = $eiPrev;
                $res[] = $ei;
            }
        }
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($res);
    }


    public function modifForm()
    {
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
                $res[] = 'ue existe pass';
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

    public function creerForm(){
        $app = Slim::getInstance();
        $val = $app->request->post();
        $nom = filter_var($val['nom'],FILTER_SANITIZE_STRING);
        $fst = filter_var($val['fst'], FILTER_SANITIZE_NUMBER_INT,FILTER_NULL_ON_FAILURE);
        Formation::creerForm($nom,$fst);
        $app->response->headers->set('Content-Type', 'application/json');
        $res = array();
        $res[] = 'true';
        echo json_encode($res);
    }


}