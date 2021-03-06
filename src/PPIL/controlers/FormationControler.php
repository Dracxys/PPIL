<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 16/05/2017
 * Time: 11:27
 */

namespace PPIL\controlers;

use League\Csv\Writer;
use PPIL\models\Enseignant;
use PPIL\models\Formation;
use PPIL\models\Intervention;
use PPIL\models\Notification;
use PPIL\models\NotificationIntervention;
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
                $f = Formation::where('fst', '=', '1')->get();
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
                    if (!empty($f)) {
                        if (!in_array($f->nomFormation, $res)) {
                            $res[] = $f->nomFormation;
                        }
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
        $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        if (self::verif($val)) {
            $ue = UE::where('id_UE', '=', $id)->first();
            if (!empty($ue)) {
                $heuresCM = filter_var($val['heureCM'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);;
                $heuresTD = filter_var($val['heureTD'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $heuresTP = filter_var($val['heureTP'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $heuresEI = filter_var($val['heureEI'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeTD = filter_var($val['nbGroupeTD'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeTP = filter_var($val['nbGroupeTP'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeEI = filter_var($val['nbGroupeEI'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                UE::modifierUE($ue->id_UE, $ue->nom_UE, $heuresCM, $heuresTP, $heuresTD, $heuresEI, $groupeTP, $groupeTD, $groupeEI);
                $resp = UE::getResponsable($ue->id_UE);
                $mail = new MailControler();
                $e = Enseignant::find($_SESSION['mail']);
                $tab = array();
                foreach ($resp as $value) {
                    $mes = "Responsable : " . $e->prenom . " " . $e->nom . ".\n";
                    $mes .= "Les heures attendus de l UE " . $ue->nom_UE . " ont été modifié.";
                    $mail->sendMail($value->enseignant, "Modification des heures attendus de votre UE", $mes);
                    $tab[] = $value->enseignant;
                }
                $ens = Intervention::where('id_UE', '=', $ue->id_UE)->get();
                foreach ($ens as $value) {
                    if (!in_array($value->mail_enseignant, $tab)) {
                        $mes = "Responsable : " . $e->prenom . " " . $e->nom . ".\n";
                        $mes .= "Les heures attendus de l UE " . $ue->nom_UE . " ont été modifié.";
                        $mail->sendMail($value->mail_enseignant, "Modification des heures attendus d'un UE", $mes);
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
        ) {
            $res = true;
        }
        return $res;
    }

    public function creerForm()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $resp = array();
        $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
        $resp[] = filter_var($val['resp1'], FILTER_SANITIZE_STRING);
        $resp[] = filter_var($val['resp2'], FILTER_SANITIZE_STRING);
        $resp[] = filter_var($val['resp3'], FILTER_SANITIZE_STRING);
        $resp[] = filter_var($val['resp4'], FILTER_SANITIZE_STRING);
        if ($nom != "") {
            $fst = filter_var($val['fst'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
            $f = Formation::where('nomFormation', 'like', $nom)->first();
            if (empty($f)) {
                $id = Formation::creerForm($nom, $fst);
                foreach ($resp as $value) {
                    if ($value != '0') {
                        $ens = Enseignant::find($value);
                        if (!empty($ens)) {
                            $respon = new Responsabilite();
                            $respon->enseignant = $value;
                            $respon->intituleResp = "Responsable formation";
                            $respon->id_formation = $id;
                            $respon->privilege = 1;
                            $respon->save();
                            $mail = new MailControler();
                            $mail->sendMail($value, 'Formation', 'Vous avez été choisi comme responsable de cette formation : ' . $nom . ".");
                        } else {
                            $respon = Responsabilite::where('id_formation', '=', $id)->get();
                            foreach ($respon as $item) {
                                $item->delete();
                            }
                            $form = Formation::find($id);
                            $form->delete();
                            $app->response->headers->set('Content-Type', 'application/json');
                            $res = array();
                            $res[] = 'false';
                            echo json_encode($res);
                            return false;
                        }
                    }
                }

                $app->response->headers->set('Content-Type', 'application/json');
                $res = array();
                $res[] = 'true';
                echo json_encode($res);

            } else {
                $app->response->headers->set('Content-Type', 'application/json');
                $res = array();
                $res[] = 'false';
                echo json_encode($res);
            }
        } else {
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res[] = 'false';
            echo json_encode($res);
        }

    }


    public function supprimerUE()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        $ue = UE::find($id);
        if (!empty($ue)) {
            $inter = Intervention::where('id_UE', '=', $id)->get();
            $c = new MailControler();
            foreach ($inter as $value) {
                if ($_SESSION['mail'] != $value->mail_enseignant) {
                    $c->sendMail($value->mail_enseignant, "UE supprimé", "UE : " . $ue->nom_UE . " a été supprimé.");
                }
                $value->delete();
                $e = Enseignant::find($value->mail_enseignant);
                Enseignant::conversionHeuresTD($e);
            }
            $resp = Responsabilite::where('id_UE', '=', $id)->get();
            foreach ($resp as $value) {
                if ($_SESSION['mail'] != $value->enseignant) {
                    $c->sendMail($value->enseignant, "UE supprimé", "Vous n'êtes plus responsable de l'UE :  " . $ue->nom_UE . ".");
                }
                $value->delete();
            }
            $notif = NotificationIntervention::where('id_UE', '=', $id)->get();
            foreach ($notif as $value) {
                $n = Notification::where('id_notification', '=', $value->id_notification)->first();
                if ($_SESSION['mail'] != $n->mail_source) {
                    $c->sendMail($value->mail_source, "UE supprimé", "UE : " . $ue->nom_UE . " a été supprimé.");
                }
                $value->delete();
                $n->delete();
            }
            $ue->delete();
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res[] = 'true';
            echo json_encode($res);
        } else {
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res[] = 'false';
            echo json_encode($res);
        }
    }

    public function recupererEnseignant()
    {
        $app = Slim::getInstance();
        $ens = Enseignant::all();
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($ens);
    }

    public function ajouterUE()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $nomUE = filter_var($val['nom'], FILTER_SANITIZE_STRING);
        $form = filter_var($val['form'], FILTER_SANITIZE_STRING);
        $resp = filter_var($val['resp'], FILTER_SANITIZE_STRING);
        if (self::verif($val)) {
            $ue = UE::where('nom_UE', 'like', $nomUE)->first();
            $f = Formation::where('nomFormation', 'like', $form)->first();
            if (empty($ue) && !empty($f)) {
                $heuresCM = filter_var($val['heureCM'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);;
                $heuresTD = filter_var($val['heureTD'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $heuresTP = filter_var($val['heureTP'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $heuresEI = filter_var($val['heureEI'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeTD = filter_var($val['nbGroupeTD'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeTP = filter_var($val['nbGroupeTP'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeEI = filter_var($val['nbGroupeEI'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                if ($resp != '0') {
                    $ens = Enseignant::find($resp);
                    if (!empty($ens)) {
                        $idUE = UE::creerUE($nomUE, $heuresCM, $heuresTP, $heuresTD, $heuresEI, $groupeTP, $groupeTD, $groupeEI, $f->id_formation, 1);
                        Responsabilite::ajoutResponsabilite($resp, 'Responsable UE', null, $idUE);
                        /*
                          $respon = new Responsabilite();
                          $respon->enseignant = $resp;
                          $respon->intituleResp = "Responsable UE";
                          $respon->id_UE = $idUE;
                          $respon->privilege = 0;
                          $respon->save();
                        */
                        $mail = new MailControler();
                        $mail->sendMail($resp, 'UE', 'Vous avez été choisi comme responsable d\'UE : ' . $nomUE . ".");
                        $app->response->headers->set('Content-Type', 'application/json');
                        $res = array();
                        $res[] = 'true';
                        echo json_encode($res);
                        return true;
                    } else {
                        $app->response->headers->set('Content-Type', 'application/json');
                        $res = array();
                        $res[] = 'false';
                        echo json_encode($res);
                        return false;
                    }
                } else {
                    UE::creerUE($nomUE, $heuresCM, $heuresTP, $heuresTD, $heuresEI, $groupeTP, $groupeTD, $groupeEI, $f->id_formation, 1);
                    $app->response->headers->set('Content-Type', 'application/json');
                    $res = array();
                    $res[] = 'true';
                    echo json_encode($res);
                    return true;
                }
            } else {
                $app->response->headers->set('Content-Type', 'application/json');
                $res = array();
                $res[] = 'false';
                echo json_encode($res);
                return false;
            }

        } else {
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res[] = 'false';
            echo json_encode($res);
            return false;
        }
    }

    public function actualisation()
    {
        $app = Slim::getInstance();
        $app->response->headers->set('Content-Type', 'application/json');

        $e = Enseignant::find($_SESSION['mail']);
        $privi = Enseignant::get_privilege($e);
        if ($privi == 2) {
            $f = Formation::where('fst', '=', '1')->get();
            $val = array();
            foreach ($f as $value) {
                if (!in_array($value->nomFormation, $val)) {
                    $val[] = $value->nomFormation;
                }
            }
            echo json_encode($val);

        } elseif ($privi == 1) {
            $resp = Responsabilite::where('enseignant', 'like', $e->mail)->get();
            $res = array();
            foreach ($resp as $value) {
                $f = Formation::find($value->id_formation);
                if (!empty($f)) {
                    if (!in_array($f->nomFormation, $res)) {
                        $res[] = $f->nomFormation;
                    }
                }

            }
            echo json_encode($res);

        }
    }

    public function supprimerForm()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
        $f = Formation::where('nomFormation', 'like', $nom)->first();
        $c = new MailControler();
        if (!empty($f)) {
            $lotUE = UE::where('id_formation', '=', $f->id_formation)->get();
            foreach ($lotUE as $ue) {
                $id = $ue->id_UE;
                $inter = Intervention::where('id_UE', '=', $id)->get();
                foreach ($inter as $value) {
                    if ($_SESSION['mail'] != $value->mail_enseignant) {
                        $c->sendMail($value->mail_enseignant, "UE supprimé", "UE : " . $ue->nom_UE . " a été supprimé.");
                    }
                    $value->delete();
                    $e = Enseignant::find($value->mail_enseignant);
                    Enseignant::conversionHeuresTD($e);
                }
                $resp = Responsabilite::where('id_UE', '=', $id)->get();
                foreach ($resp as $value) {
                    if ($_SESSION['mail'] != $value->enseignant) {
                        $c->sendMail($value->enseignant, "UE supprimé", "Vous n'êtes plus responsable de l'UE :  " . $ue->nom_UE . ".");
                    }
                    $value->delete();
                }
                $notif = NotificationIntervention::where('id_UE', '=', $id)->get();
                foreach ($notif as $value) {
                    $n = Notification::where('id_notification', '=', $value->id_notification)->first();
                    if ($_SESSION['mail'] != $n->mail_source) {
                        $c->sendMail($value->mail_source, "UE supprimé", "UE : " . $ue->nom_UE . " a été supprimé.");
                    }
                    $value->delete();
                    $n->delete();
                }
                $ue->delete();
            }
            $respons = Responsabilite::where('id_formation', '=', $f->id_formation)->get();
            foreach ($respons as $v) {
                if ($_SESSION['mail'] != $v->enseignant) {
                    $c->sendMail($v->enseignant, "Formation supprimé", "La formation : " . $f->nomFormation . " a été supprimée.");
                }
                $v->delete();
            }
            $f->delete();
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res[] = 'true';
            echo json_encode($res);
            return true;

        } else {
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res[] = 'false';
            echo json_encode($res);
            return false;
        }
    }

    public function form()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
        $f = Formation::where('nomFormation', 'like', $nom)->first();
        if (!empty($f)) {
            $resp = Responsabilite::where('id_formation', '=', $f->id_formation)->get();
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode($resp);
            return true;
        } else {
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            echo json_encode($res);
            return false;
        }
    }

    public function modifierForm()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $ancienNom = filter_var($val['ancienNom'], FILTER_SANITIZE_STRING);
        $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
        $f = Formation::where('nomFormation', 'like', $ancienNom)->first();
        $resp = array();
        $c = new MailControler();
        if (!empty($f)) {
            if ($nom != "") {
                $f1 = Formation::where('nomFormation','like',$nom)->first();
                $bon = true;
                if(!empty($f1)){
                    if($f1->id_formation != $f->id_formation){
                        $bon = false;
                    }
                }
                if($bon){
                    $f->nomFormation = $nom;
                    $f->save();
                    $resp[] = filter_var($val['resp1'], FILTER_SANITIZE_STRING);
                    $resp[] = filter_var($val['resp2'], FILTER_SANITIZE_STRING);
                    $resp[] = filter_var($val['resp3'], FILTER_SANITIZE_STRING);
                    $resp[] = filter_var($val['resp4'], FILTER_SANITIZE_STRING);
                    $respons = Responsabilite::where('id_formation', '=', $f->id_formation)->get();
                    foreach ($respons as $value) {
                        if (!in_array($value->enseignant, $resp)) {
                            $c->sendMail($value->enseignant, 'Formation', "Vous n'êtes plus responsable de cette formation : " . $f->nomFormation . ".");
                            $value->delete();
                        } else {
                            unset($resp[array_search($value->enseignant, $resp)]);
                        }
                    }
                    foreach ($resp as $value) {
                        if ($value != '0') {
                            $respon = new Responsabilite();
                            $respon->enseignant = $value;
                            $respon->intituleResp = "Responsable formation";
                            $respon->id_formation = $f->id_formation;
                            $respon->privilege = 1;
                            $respon->save();
                            $c->sendMail($value, 'Formation', 'Vous avez été choisi comme responsable de cette formation : ' . $nom . ".");
                        }
                    }
                    $app->response->headers->set('Content-Type', 'application/json');
                    $res = array();
                    $res[] = 'true';
                    echo json_encode($res);
                    return true;
                }else{
                    $app->response->headers->set('Content-Type', 'application/json');
                    $res = array();
                    $res[] = 'false';
                    echo json_encode($res);
                    return false;
                }

            } else {
                $app->response->headers->set('Content-Type', 'application/json');
                $res = array();
                $res[] = 'false';
                echo json_encode($res);
                return false;
            }
        } else {
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res[] = 'false';
            echo json_encode($res);
            return false;
        }
    }

    public function exporter($nom)
    {
        if (isset($_SESSION['mail'])) {
            $app = Slim::getInstance();
            //$val = $app->request->post();
            $nom = filter_var($nom, FILTER_SANITIZE_STRING);
            //$nom = 'Master Informatique';
            $form = Formation::where('nomFormation', 'like', $nom)->first();
            if (!empty($form)) {
                $csv_form = Writer::createFromFileObject(new \SplTempFileObject());
                $csv_form->insertOne([$nom]);
                $csv_form->insertOne([]);
                $csv_form->insertOne([]);
                $csv_form->insertOne([]);
                $csv_form->insertOne(['nom UE', 'Volume attendu CM', 'Volume affecté CM', 'Nb groupes attendus TD', 'Nb groupes avec enseignant TD', 'Volume attendu TD', 'Volume affecté TD', 'Nb groupes attendus TP', 'Nb groupes avec enseignant TP', 'Volume attendu TP', 'Volume affecté TP', 'Nb groupes attendus EI', 'Nb groupes avec enseignant EI', 'Volume attendu EI', 'Volume affecté EI']);
                $ueAll = UE::where('id_formation', '=', $form->id_formation)->get();
                $totalHeuresCM = 0;
                $totalPrevisionHeuresCM = 0;
                $totalHeuresTD = 0;
                $totalPrevisionHeuresTD = 0;
                $totalHeuresTP = 0;
                $totalPrevisionHeuresTP = 0;
                $totalHeuresEI = 0;
                $totalPrevisionHeuresEI = 0;
                foreach ($ueAll as $ue) {
                    $ligne = array(
                        $ue->nom_UE,
                        $ue->prevision_heuresCM,
                        $ue->heuresCM,
                        $ue->prevision_groupeTD,
                        $ue->groupeTD,
                        $ue->prevision_heuresTD,
                        $ue->heuresTD,
                        $ue->prevision_groupeTP,
                        $ue->groupeTP,
                        $ue->prevision_heuresTP,
                        $ue->heuresTP,
                        $ue->prevision_groupeEI,
                        $ue->groupeEI,
                        $ue->prevision_heuresEI,
                        $ue->heuresEI,
                    );

                    $totalHeuresCM += $ue->heuresCM;
                    $totalPrevisionHeuresCM += $ue->prevision_heuresCM;
                    $totalHeuresTD += $ue->heuresTD;
                    $totalPrevisionHeuresTD += $ue->prevision_heuresTD;
                    $totalHeuresTP += $ue->heuresTP;
                    $totalPrevisionHeuresTP += $totalPrevisionHeuresTP;
                    $totalHeuresEI += $ue->heuresEI;
                    $totalPrevisionHeuresEI += $ue->prevision_heuresEI;

                    $csv_form->insertOne($ligne);
                }
                $csv_form->insertOne([]);
                $csv_form->insertOne([]);
                $csv_form->insertOne([]);
                $csv_form->insertOne(['Total', 'Volume attendu CM', 'Volume affecté CM', 'Volume attendu TD', 'Volume affecté TD', 'Volume attendu TP', 'Volume affecté TP', 'Volume attendu EI', 'Volume affecté EI']);
                $ligne = array(
                    $nom,
                    $totalPrevisionHeuresCM,
                    $totalHeuresCM,
                    $totalPrevisionHeuresTD,
                    $totalHeuresTD,
                    $totalPrevisionHeuresTP,
                    $totalHeuresTP,
                    $totalPrevisionHeuresEI,
                    $totalHeuresEI,
                );
                $csv_form->insertOne($ligne);

                $csv_form->output($nom.'.csv');
            }
        } else {
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }


}