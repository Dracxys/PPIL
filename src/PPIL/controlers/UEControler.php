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

use League\Csv\Writer;
use League\Csv\Reader;

use Slim\Slim;


class UEControler
{
    public function home()
    {
        if (isset($_SESSION['mail'])) {
            $res = self::get_ues();
            $v = new VueUe();
            echo $v->home($res);
        } else {
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }

    }

    private function get_ues()
    {
        $e = Enseignant::find($_SESSION['mail']);
        $respon = Responsabilite::where('enseignant', 'like', $e->mail)->first();
        $res = array();
        if (!empty($respon)) {
            $privi = Enseignant::get_privilege($e);
            if ($privi == 2) {
                $ue = UE::where('fst', '=', 1)->get();
                foreach ($ue as $value) {
                    if (!in_array($value, $res)) {
                        $res[] = $value;
                    }
                }
            } elseif ($privi == 1) {
                $resp = Responsabilite::where('enseignant', 'like', $e->mail)->get();
                foreach ($resp as $value) {
                    $ue = UE::where('id_formation', '=', $value->id_formation)->first();
                    if (!in_array($ue, $res)) {
                        $res[] = $ue;
                    }
                    $ue = UE::where('id_UE', '=', $value->id_UE)->first();
                    if (!in_array($ue, $res)) {
                        $res[] = $ue;
                    }
                }
                $intervention = Intervention::where('mail_enseignant', 'like', $e->mail)->get();
                foreach ($intervention as $value) {
                    $ue = UE::where('id_UE', '=', $value->id_UE)->where('fst', 'like', 1)->first();
                    if (!in_array($ue, $res)) {
                        $res[] = $ue;
                    }
                }
            } else {
                $resp = Responsabilite::where('enseignant', 'like', $e->mail)->get();
                foreach ($resp as $value) {
                    $ue = UE::where('id_UE', '=', $value->id_UE)->first();
                    if (!in_array($ue, $res)) {
                        $res[] = $ue;
                    }
                }
                $intervention = Intervention::where('mail_enseignant', 'like', $e->mail)->get();
                foreach ($intervention as $value) {
                    $ue = UE::where('id_UE', '=', $value->id_UE)->where('fst', '=', 1)->first();
                    if (!in_array($ue, $res)) {
                        $res[] = $ue;
                    }
                }
            }
        } else {
            $res = array();
            $intervention = Intervention::where('mail_enseignant', 'like', $e->mail)->get();
            foreach ($intervention as $value) {
                $ue = UE::where('id_UE', '=', $value->id_UE)->where('fst', '=', 1)->first();
                if (!in_array($ue, $res)) {
                    $res[] = $ue;
                }
            }
        }
        return $res;
    }


    public function exporter()
    {
        if (isset($_SESSION['mail'])) {
            $ues = self::get_ues();
            $csv_ues = Writer::createFromFileObject(new \SplTempFileObject());
            $csv_array = array();
            $headers_intervenants = null;
            if (!empty($ues)) {
                //                $headers_ues = $ues[0]->getTableColumns();
                $csv_ues->insertOne(['intitulé', 'nom formation', 'mail Enseignant', 'heuresCM', 'heuresTP', 'heuresTD', 'heuresEI', 'groupeTP', 'groupeTD', 'groupeEI']);
                foreach ($ues as $ue) {
                    if (!empty($ue)) {
                        $interventions = Intervention::where('id_UE', '=', $ue->id_UE)->get();
                        $formation = Formation::where('id_formation', '=', $ue->id_formation)->first();

                        if (!$interventions->isEmpty() && !empty($formation)) {
                            $csv_intervenants = array();
                            $headers_intervenants = $interventions->first()->getTableColumns();
                            foreach ($interventions as $i) {
                                //$csv_intervenants[] = $i;
                                $ligne = array(
                                    $ue->nom_UE,
                                    $formation->nomFormation,
                                    $i->mail_enseignant,
                                    //                                $i->fst==true ? 'FST' : 'Hors FST',
                                    $i->heuresCM,
                                    $i->heuresTP,
                                    $i->heuresTD,
                                    $i->heuresEI,
                                    $i->groupeTP,
                                    $i->groupeTD,
                                    $i->groupeEI
                                );
                                $csv_ues->insertOne($ligne);
                            }
                            //  $csv_array[] = $csv_intervenants;
                        }
                    }

                    //                    $csv_ues->insertOne($ue->toArray());
                }
                /*
                foreach($csv_array as $interv){
                    $csv_ues->insertOne([]);
                    $csv_ues->insertOne($headers_intervenants);
                    foreach ($interv as $i) {
                        $csv_ues->insertOne($i->toArray());
                    }
                    }*/
                $csv_ues->output('ues.csv');
            }
        } else {
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function importer()
    {
        if (isset($_SESSION['mail'])) {
            $user = Enseignant::where("mail", "like", $_SESSION['mail'])->first();

            $app = Slim::getInstance();
            $app->response->headers->set('Content-Type', 'application/json');

            $erreur = true;
            $message = array(
                'error' => false,
                'size' => false,
                'extension' => false,
                'parse' => false
            );

            if ($_FILES['file']['size'] > 0) {
                $chemin = "imports/";
                $root = Slim::getInstance()->root();
                $extensions_valides = array('csv');
                $maxsize = 20971520;
                $extension_upload = strtolower(substr(strrchr($_FILES['file']['name'], '.'), 1));
                $nameFile = md5(uniqid(rand(), true));
                $ues = array();
                if ($_FILES['file']['error'] > 0) {
                    $message['error'] = true;
                } else if ($_FILES['file']['size'] > $maxsize) {
                    $message['size'] = true;
                } else if (!in_array($extension_upload, $extensions_valides)) {
                    $message['extension'] = true;
                } else {
                    move_uploaded_file($_FILES['file']['tmp_name'], $root . $chemin . $nameFile . "." . $extension_upload);

                    # this is where the magic happens
                    $csv = Reader::createFromPath($root . $chemin . $nameFile . "." . $extension_upload);
                    $csv->setOffset(1);
                    $nb_insert = $csv->each(function ($row, $rowOffset) use (&$message) {
                        $result = true;

                        # L'enseignant existe ? Sinon, on arrete
                        $e = Enseignant::where('mail', '=', $row[2])->first();
                        if (is_null($e)) {
                            $message['parse'] = true;
                            $result = false;
                        }
                        if ($result) {
                            # est ce que la formation avec ce nom existe ?
                            $f = Formation::where('nomFormation', '=', $row[1])->first();
                            if (is_null($f)) {
                                # non, on la crée
                                $f = new Formation();
                                $f->nomFormation = $row[1];
                                $f->save();
                            }

                            $i = null;

                            # On cherche l'UE
                            $ue = UE::where('nom_UE', "=", $row[0])
                                ->where('id_formation', "=", $f->id_formation)
                                ->first();
                            if (is_null($ue)) {
                                # Elle n'existe pas, est ce que c'est une responsabilité ?
                                switch (strtolower($row[0])) {
                                    case "responsable ue":
                                        $r = Responsabilite::where('enseignant', '=', $row[2])
                                            ->where('intituleResp', '=', 'responsable ue')
                                            ->first();
                                        # On ne crée pas la responsibilité si elle n'existe pas.
                                        if (!empty($r)) {
                                            $i = Intervention::where('mail_enseignant', '=', $row[2])
                                                ->where('id_responsabilite', '=', $r->id_resp)
                                                ->first();
                                        } else {
                                            $message['parse'] = true;
                                        }
                                        break;
                                    case "responsable formation":
                                        $r = Responsabilite::where('enseignant', '=', $row[2])
                                            ->where('intituleResp', '=', 'responsable formation')
                                            ->first();
                                        # On ne crée pas la responsibilité si elle n'existe pas.
                                        if (!empty($r)) {
                                            $i = Intervention::where('mail_enseignant', '=', $row[2])
                                                ->where('id_responsabilite', '=', $r->id_resp)
                                                ->first();
                                        } else {
                                            $message['parse'] = true;
                                        }
                                        break;
                                    case "responsable di":
                                        $r = Responsabilite::where('enseignant', '=', $row[2])
                                            ->where('intituleResp', '=', 'responsable du departement informatique')
                                            ->first();
                                        # On ne crée pas la responsibilité si elle n'existe pas.
                                        if (!empty($r)) {
                                            $i = Intervention::where('mail_enseignant', '=', $row[2])
                                                ->where('id_responsabilite', '=', $r->id_resp)
                                                ->first();
                                        } else {
                                            $message['parse'] = true;
                                        }
                                        break;
                                    default:
                                        # Non, on la crée.
                                        $ue = new UE();
                                        $ue->nom_UE = $row[0];
                                        $ue->fst = true;
                                        $ue->id_formation = $f->id_formation;
                                        $ue->save();

                                        # On cherche si l'intervention existe
                                        $i = Intervention::where('mail_enseignant', '=', $row[2])
                                            ->where('id_UE', '=', $ue->id_UE)
                                            ->first();
                                        if (is_null($i)) {
                                            # Non, on la crée
                                            $i = new Intervention();
                                            $i->mail_enseignant = $row[2];
                                            $i->id_UE = $ue->id_UE;
                                            $i->fst = true;
                                        }
                                        break;
                                }
                            }

                            if (!is_null($i)) {
                                $i->heuresTD = $row[3];
                                $i->heuresTP = $row[4];
                                $i->heuresCM = $row[5];
                                $i->heuresEI = $row[6];
                                $i->groupeTD = $row[7];
                                $i->groupeTP = $row[8];
                                $i->groupeEI = $row[9];
                                $i->save();
                                $result = true;
                            }


                        }
                        return $result;
                    });
                    if (!$message['parse']) {
                        $erreur = false;
                    }
                }
            }
            echo json_encode([
                "error" => $erreur,
                "messages" => $message
            ]);
        } else {
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function infoUE()
    {
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

    public function intervenantsUE()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $app->response->headers->set('Content-Type', 'application/json');
        $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);
        echo json_encode(self::get_intervenantsUE($id));
    }

    private function get_intervenantsUE($id)
    {
        $interventions = Intervention::where('id_UE', 'like', $id)->get();
        $res = array();
        if (!empty($interventions)) {
            foreach ($interventions as $value) {
                $user = Enseignant::where('mail', 'like', $value->mail_enseignant)->first();
                $res[] = $user->nom;
                $res[] = $user->prenom;
                $res[] = $value->heuresCM;
                $res[] = $value->groupeTD;
                $res[] = $value->heuresTD;
                $res[] = $value->groupeTP;
                $res[] = $value->heuresTP;
                $res[] = $value->groupeEI;
                $res[] = $value->heuresEI;
                $res[] = $user->mail;
            }
        }
        return $res;
    }

    public function boutonModif()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $app->response->headers->set('Content-Type', 'application/json');
        $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);
        $return = false;
        if (isset($_SESSION['mail'])) {
            $user = Enseignant::where('mail', 'like', $_SESSION['mail'])->first();
            $respon = Responsabilite::where('enseignant', 'like', $user->mail)->get();
            if (!empty($respon)) {
                $privi = Enseignant::get_privilege($user);
                if ($privi == 2) {
                    $return = true;
                } elseif ($privi == 0) {
                    foreach ($respon as $value) {
                        if ($value->id_UE == $id) {
                            $return = true;
                            break;
                        }
                    }
                } elseif ($privi == 1) {
                    $ue = UE::find($id);
                    foreach ($respon as $value) {
                        if ($value->id_formation == $ue->id_formation) {
                            $return = true;
                            break;
                        }
                    }
                }
            }
        }
        echo json_encode($return);
    }

    public function creerUE()
    {
        if (isset($_SESSION['mail'])) {
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

            if (!empty($nom_responsable)) {
                $responsable = Enseignant::where('nom', 'like', $nom_responsable)->first();
                if (empty(responsable)) {
                    // *************************** ERREUR : L'ENSIGNANT N'EXISTE PAS
                } else {
                    $nouvUE = UE::where('nom_UE', 'like', $nom)->first();
                    ajoutResponsabilite($responsable->mail, 'responsable ue', null, $nouvUE->id_UE);
                }
            }

        }
    }

    public function modifierUE()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        if (self::verif($val)) {
            $ue = UE::where('id_UE', '=', $id)->first();
            if (!empty($ue)) {
                $heuresCM = filter_var($val['heureCM'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
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
        ) {
            $res = true;
        }
        return $res;
    }

    public function modifierHeureEnseignant()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $idUE = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        $mail = filter_var($val['mail'], FILTER_SANITIZE_EMAIL);
        $inter = Intervention::where('mail_enseignant', 'like', $mail)->where('id_UE', '=', $idUE)->first();
        if (!empty($inter)) {
            $ue = UE::find($idUE);
            if (self::verif($val)) {
                $heuresCM = filter_var($val['heureCM'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $heuresTD = filter_var($val['heureTD'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $heuresTP = filter_var($val['heureTP'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $heuresEI = filter_var($val['heureEI'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeTD = filter_var($val['nbGroupeTD'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeTP = filter_var($val['nbGroupeTP'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $groupeEI = filter_var($val['nbGroupeEI'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
                $tmpHeuresCM = $inter->heuresCM;
                $tmpHeuresTP = $inter->heuresTP;
                $tmpHeuresTD = $inter->heuresTD;
                $tmpHeuresEI = $inter->heuresEI;
                $tmpGroupeTP = $inter->groupeTP;
                $tmpGroupeTD = $inter->groupeTD;
                $tmpGroupeEI = $inter->groupeEI;
                Intervention::modifierIntervention($inter, $heuresCM, $heuresTD, $heuresTP, $heuresEI, $groupeTD, $groupeTP, $groupeEI);
                $ue = UE::find($inter->id_UE);
                $error = false;
                if ($ue->heuresCM > $ue->prevision_heuresCM) {
                    $error = true;
                }
                if ($ue->heuresTP > $ue->prevision_heuresTP) {
                    $error = true;
                }
                if ($ue->heuresTD > $ue->prevision_heuresTD) {
                    $error = true;
                }
                if ($ue->heuresEI > $ue->prevision_heuresEI) {
                    $error = true;
                }
                if ($ue->groupeTP > $ue->prevision_groupeTP) {
                    $error = true;
                }
                if ($ue->groupeTD > $ue->prevision_groupeTD) {
                    $error = true;
                }
                if ($ue->groupeEI > $ue->prevision_groupeEI) {
                    $error = true;
                }
                if ($error) {
                    Intervention::modifierIntervention($inter, $tmpHeuresCM, $tmpHeuresTD, $tmpHeuresTP, $tmpHeuresEI, $tmpGroupeTD, $tmpGroupeTP, $tmpGroupeEI);
                    $app->response->headers->set('Content-Type', 'application/json');
                    $res = array();
                    $res[] = 'Depassement';
                    echo json_encode($res);
                } else {
                    $c = new MailControler();
                    $c->sendMail($mail, "Modification intervention", "Votre intervention dans l'UE " . $ue->nom_UE . " a été modifiée par un responsable.");
                    $app->response->headers->set('Content-Type', 'application/json');
                    $res = array();
                    $res[] = 'true';
                    echo json_encode($res);
                }
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

    public function supprimerEnseignant()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $idUE = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        $mail = filter_var($val['mail'], FILTER_SANITIZE_EMAIL);
        $inter = Intervention::where('mail_enseignant', 'like', $mail)->where('id_UE', '=', $idUE)->first();
        if (!empty($inter)) {
            $ue = UE::find($idUE);
            $inter->delete();
            $resp = Responsabilite::where('enseignant', 'like', $mail)->where('id_UE', '=', $idUE)->first();
            if (!empty($resp)) {
                $resp->delete();
            }
            $e = Enseignant::find($mail);
            Enseignant::conversionHeuresTD($e);
            UE::recalculer($ue);
            $c = new MailControler();
            $c->sendMail($mail, "Intervention supprimée", "Votre intervention dans l'UE " . $ue->nom_UE . " a été supprimée par un responsable.");
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res = true;
            echo json_encode($res);
        } else {
            $app->response->headers->set('Content-Type', 'application/json');
            $res = array();
            $res = false;
            echo json_encode($res);
        }
    }

    public function listeAjoutEnseignant()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $app->response->headers->set('Content-Type', 'application/json');
        $idUE = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        $users = Enseignant::where("mail", '<>', $_SESSION['mail'])->get();
        $intervention = Intervention::where('id_UE', '=', $idUE)->get();
        $trouve = false;
        $res = array();
        foreach ($users as $u) {
            foreach ($intervention as $inter) {
                if ($u->mail == $inter->mail_enseignant) {
                    $trouve = true;
                }
            }
            if ($trouve) {
                $trouve = false;
            } else {
                $res[] = $u->nom;
                $res[] = $u->prenom;
                $res[] = $u->mail;
            }
        }
        echo json_encode($res);
    }

    public function addInterventions()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $app->response->headers->set('Content-Type', 'application/json');
        $idUE = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        $res = true;
        $ue = UE::find($idUE);
        foreach ($val['mail'] as $value) {
            $mail = filter_var($value, FILTER_SANITIZE_EMAIL, FILTER_NULL_ON_FAILURE);
            if (empty($mail)) {
                echo "mail \n";
                $res = false;
            } elseif ($res) {
                $tmp = Enseignant::find($mail);
                if (empty($tmp)) {
                    $res = false;
                    echo "enseignant \n";
                } elseif ($res) {
                    $message = $tmp->prenom . " " . $tmp->nom . ',' . "\n";
                    $message .= "vous avez été convié à participer à l'UE : " . $ue->nom_UE;
                    $newIntervention = new Intervention();
                    $newIntervention->mail_enseignant = $mail;
                    $newIntervention->id_UE = $idUE;
                    $newIntervention->fst = 1;
                    $newIntervention->save();
                    $mail = new MailControler();
                    $mail->sendMail($value, "Ajout à un UE.", $message);
                }
            }
        }
        echo json_encode($res);
    }

    public function infoRespUE()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $app->response->headers->set('Content-Type', 'application/json');
        $idUE = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        $resp = Responsabilite::where('id_UE', '=', $idUE)->first();
        $res = array();
        if (empty($resp)) {
            $res[] = 0;
            $res[] = "aucun";
            $allEns = Enseignant::all();
            foreach ($allEns as $value) {
                $tmp = $value->nom . " " . $value->prenom;
                $res[] = $value->mail;
                $res[] = $tmp;
            }
        } else {
            $ens = Enseignant::find($resp->enseignant);
            $res[] = $ens->mail;
            $res[] = $ens->nom . " " . $ens->prenom;
            $allEns = Enseignant::where('mail', '<>', $ens->mail)->get();
            foreach ($allEns as $value) {
                $tmp = $value->nom . " " . $value->prenom;
                $res[] = $value->mail;
                $res[] = $tmp;
            }
        }

        echo json_encode($res);
    }

    public function modifUE()
    {
        $app = Slim::getInstance();
        $val = $app->request->post();
        $app->response->headers->set('Content-Type', 'application/json');
        $idUE = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
        $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
        $resp = filter_var($val['resp'], FILTER_SANITIZE_EMAIL);
        $respon = Responsabilite::where('id_UE', '=', $idUE)->first();
        $ue = UE::find($idUE);
        $mail = new MailControler();
        if (!empty($ue)) {
            $ue2 = UE::where('nom_UE', 'like', $nom)->first();
            $bon = true;
            if (!empty($existe)) {
                if ($ue->id_UE != $ue2->id_UE) {
                    $bon = false;
                }
            }
            if ($bon && $nom != '') {
                $ue->nom_UE = $nom;
                if (empty($respon)) {
                    if ($resp != '0') {
                        $ens = Enseignant::find($resp);
                        if (!empty($ens)) {
                            Responsabilite::ajoutResponsabilite($resp, 'Responsable UE', null, $idUE);
                            /*
                              $respon = new Responsabilite();
                              $respon->enseignant = $resp;
                              $respon->id_UE = $idUE;
                              $respon->intituleResp = "Responsable UE";
                              $respon->privilege = 0;
                              $respon->save();
                            */
                            $ue->save();
                            $mail->sendMail($resp, "Responsable UE", "Vous êtes devenu responsable UE : " . $ue->nom_UE . ".");
                            $res = array();
                            $res[] = 'true';
                            echo json_encode($res);
                        } else {
                            $res = array();
                            $res[] = 'false';
                            echo json_encode($res);
                        }
                    } else {
                        $ue->save();
                        $res = array();
                        $res[] = 'true';
                        echo json_encode($res);
                    }
                } else {
                    if ($resp == '0') {
                        $mail->sendMail($respon->enseignant, "Responsable UE", "Vous n'êtes plus responsable UE : " . $ue->nom_UE . ".");
                        Responsabilite::supprimerResponsabilite($respon->enseignant, $respon->id_formation, $respon->id_UE);
                        $respon->delete();
                        $res = array();
                        $res[] = 'true';
                        echo json_encode($res);
                    } else {
                        $ens = Enseignant::find($resp);
                        if (empty($ens)) {
                            $res = array();
                            $res[] = 'false';
                            echo json_encode($res);
                        } else {
                            Responsabilite::modifResponsabilite($respon->id_resp, $resp, 'Responsable UE', null, $respon->$id_UE);
                            //                            $respon->enseignant = $resp;
                            //$respon->save();
                            $mail->sendMail($resp, "Responsable UE", "Vous êtes devenu responsable UE : " . $ue->nom_UE . ".");
                            $res = array();
                            $res[] = 'true';
                            echo json_encode($res);
                        }
                    }
                }
            }
        } else {
            $res = array();
            $res[] = 'false';
            echo json_encode($res);
        }
    }
}