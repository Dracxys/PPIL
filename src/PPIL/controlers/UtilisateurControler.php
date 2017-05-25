<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 13/05/2017
 * Time: 22:00
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\models\Formation;
use PPIL\models\NotificationResponsabilite;
use PPIL\models\Responsabilite;
use PPIL\views\VueHome;
use PPIL\views\VueModifProfil;
use PPIL\views\VueUtilisateur;
use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use PPIL\models\NotificationIntervention;
use PPIL\models\Intervention;
use PPIL\models\UE;

use League\Csv\Writer;
use League\Csv\Reader;

use Slim\Slim;


class UtilisateurControler
{

    public function home(){
        if(isset($_SESSION['mail'])){
            $v = new VueModifProfil();
            $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
            echo $v->home($e,-1);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function enseignement(){
        if(isset($_SESSION['mail'])){
            $v = new VueUtilisateur();
            echo $v->enseignement();
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function enseignement_exporter(){
        if(isset($_SESSION['mail'])){
            $intervention = Intervention::all();
            $csv = Writer::createFromFileObject(new \SplTempFileObject());
            //$csv->setDelimiter(';');
            $csv->insertOne($intervention->first()->getTableColumns());
            foreach($intervention as $i){
                $csv->insertOne($i->toArray());
            }
            $csv->output('interventions.csv');
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function enseignement_action(){
        if(isset($_SESSION['mail'])){
            $previsions = array(
                'heuresCM' => false,
                'heuresTP' => false,
                'heuresTD' => false,
                'heuresEI' => false,
                'groupeTP' => false,
                'groupeTD' => false,
                'groupeEI' => false
            );
            $depassement = 0;

            $val = Slim::getInstance()->request->post();
            $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);
            $id_UE = filter_var($val['id_UE'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
            $notification_exist = false;
            $error = false;

            $infos = array(
                'heuresCM' => filter_var($val['heuresCM'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE),
                'heuresTD' => filter_var($val['heuresTD'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE),
                'heuresTP' => filter_var($val['heuresTP'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE),
                'heuresEI' => filter_var($val['heuresEI'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE),
                'groupeTD' => filter_var($val['groupeTD'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE),
                'groupeTP' => filter_var($val['groupeTP'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE),
                'groupeEI' => filter_var($val['groupeEI'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE)
            );
            $supprime = $val['supprime']==false ? false : filter_var($val['supprime'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            foreach($infos as $nom => $data){
                if(!($data != null && $data >= 0)){
                    $error = true;
                }
            }
            if($id_UE == null || $id_UE < 0){
                $error = true;
                if($id != null && $id >= 0){
                    $error = false;
                }
            }

            $notifications = Notification::where('mail_destinataire', '=', $_SESSION['mail'])
                           ->where('type_notification', '=', 'PPIL\models\NotificationIntervention')
                           ->get();
            foreach($notifications as $notification){
                $notification_intervention = NotificationIntervention::where('id_notification', '=', $notification->id_notification)
                                           ->where('id_UE', '=', $id_UE)
                                           ->first();
                if(!empty($notification_intervention)){
                    $error = true;
                    $notification_exist = true;
                }
            }

            if(!$error){
                # Pas de notification en attente
                $i = Intervention::where('id_intervention', '=', $id)
                   ->first();
                if(!empty($i)){
                    # L'intervention existe
                    if($supprime){
                        # Supprime l'intervention...
                        if($i->id_responsabilite == null){
                            # ... Sauf si elle est liée à une responsabilité
                            $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
                            Enseignant::modifie_intervention($e, $id, $id_UE, $infos, $supprime, null, null, false);
                        }
                    }

                    if(!($i->id_intervention == $id
                       && $i->heuresCM == $infos['heuresCM']
                       && $i->heuresTP == $infos['heuresTP']
                       && $i->heuresTD == $infos['heuresTD']
                       && $i->heuresEI == $infos['heuresEI']
                       && $i->groupeTP == $infos['groupeTP']
                       && $i->groupeTD == $infos['groupeTD']
                       && $i->groupeEI == $infos['groupeEI']
                       && $i->mail_enseignant == $_SESSION['mail']
                       && $i->id_UE == $id_UE)){
                        # il y a des changements
                        $tmpHeuresCM = $i->heuresCM;
                        $tmpHeuresTP = $i->heuresTP;
                        $tmpHeuresTD = $i->heuresTD;
                        $tmpHeuresEI = $i->heuresEI;
                        $tmpGroupeTP = $i->groupeTP;
                        $tmpGroupeTD = $i->groupeTD;
                        $tmpGroupeEI = $i->groupeEI;
                        Intervention::modifierIntervention($i,$infos['heuresCM'],$infos['heuresTD'],$infos['heuresTP'],$infos['heuresEI'],$infos['groupeTD'],$infos['groupeTP'],$infos['groupeEI']);

                        if($i->id_UE != null){
                            # On intervient sur une UE
                            $ue = UE::find($i->id_UE);
                            if($ue->heuresCM > $ue->prevision_heuresCM){
                                $previsions['heuresCM'] = true;
                                $error = true;
                            }
                            if($ue->heuresTP > $ue->prevision_heuresTP){
                                $error = true;
                                $previsions['heuresTP'] = true;
                            }
                            if($ue->heuresTD > $ue->prevision_heuresTD){
                                $previsions['heuresTD'] = true;
                                $error = true;
                            }
                            if($ue->heuresEI > $ue->prevision_heuresEI){
                                $previsions['heuresEI'] = true;
                                $error = true;
                            }
                            if($ue->groupeTP > $ue->prevision_groupeTP){
                                $previsions['groupeTP'] = true;
                                $error = true;
                            }
                            if($ue->groupeTD > $ue->prevision_groupeTD){
                                $previsions['groupeTD'] = true;
                                $error = true;
                            }
                            if($ue->groupeEI > $ue->prevision_groupeEI){
                                $previsions['groupeEI'] = true;
                                $error = true;
                            }
                            if(!$error){
                                # ne dépasse pas les horaires prévus
                                # dépasse ses horaires max ?
                                $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
                                $depassement = $e->volumeCourant - $e->volumeMax;

                                Intervention::modifierIntervention($i,$tmpHeuresCM,$tmpHeuresTD,$tmpHeuresTP,$tmpHeuresEI,$tmpGroupeTD,$tmpGroupeTP,$tmpGroupeEI);
                                Enseignant::modifie_intervention($e, $id, $id_UE, $id_responsabilite, $infos, $supprime, null, null, false);
                            }
                            else{
                                # on réetablit l'intervention à son état initial
                                Intervention::modifierIntervention($i,$tmpHeuresCM,$tmpHeuresTD,$tmpHeuresTP,$tmpHeuresEI,$tmpGroupeTD,$tmpGroupeTP,$tmpGroupeEI);
                            }
                        } else if($i->id_responsabilite != null){
                            $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
                            $depassement = $e->volumeCourant - $e->volumeMax;
                            $error = false;
                            # On intervient dans sa responsabilite
                        } else {
                            $error = true;
                        }
                    }
                } else {
                    $error = true;
                }
            }
            echo json_encode([
                'error' => $error,
                'notification_exist' => $notification_exist,
                'depassement' => $depassement,
                'previsions' => $previsions
            ]);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function enseignement_action_ajouter(){
        if(isset($_SESSION['mail'])){
            $val = Slim::getInstance()->request->post();
            $id_UE = filter_var($val['id_UE'], FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE);
            $error = false;

            #On cherche si il y a une intervention dans cette ue par cet enseignant
            $intervention = Intervention::where('mail_enseignant', '=', $_SESSION['mail'])
                          ->where('id_UE', '=', $id_UE)
                          ->first();

            if(empty($intervention)){
                #il n'y en a pas
                #il n'a pas déjà fait de demande ?
                $notifications = Notification::where('mail_destinataire', '=', $_SESSION['mail'])
                               ->where('type_notification', '=', 'PPIL\models\NotificationIntervention')
                               ->get();
                foreach($notifications as $notification){
                    $notification_intervention = NotificationIntervention::where('id_notification', '=', $notification->id_notification)
                                               ->where('id_UE', '=', $id_UE)
                                               ->first();
                    if(!empty($notification_intervention)){
                        $error = true;
                    }
                }
            }
            if(!$error){
                $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
                $ajout = true;
                $id = null;
                $supprime = false;
                $infos = array(
                    'heuresCM' => 0,
                    'heuresTD' => 0,
                    'heuresTP' => 0,
                    'heuresEI' => 0,
                    'groupeTD' => 0,
                    'groupeTP' => 0,
                    'groupeEI' => 0
                );
                Enseignant::modifie_intervention($e, $id, $id_UE, $infos, $supprime, null, null, $ajout);
            }
            echo json_encode([
                'error' => $error,
            ]);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function enseignement_action_ajouter_autre(){
        if(isset($_SESSION['mail'])){
            $val = Slim::getInstance()->request->post();
            $nom_UE = filter_var($val['nom_UE'], FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
            $nom_formation = filter_var($val['nom_UE'], FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE);
            $error = false;

            if(is_null($nom_UE) || is_null($nom_formation) || $nom_UE == "" || $nom_formation == ""){
                $error = true;
            }
            if(!$error){
                $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
                $id = null;
                $id_UE = null;
                $supprime = false;
                $infos = array(
                    'heuresCM' => 0,
                    'heuresTD' => 0,
                    'heuresTP' => 0,
                    'heuresEI' => 0,
                    'groupeTD' => 0,
                    'groupeTP' => 0,
                    'groupeEI' => 0
                );
                Enseignant::modifie_intervention($e, $id, $id_UE, $infos, $supprime, $nom_UE, $nom_formation, true);
            }
            echo json_encode([
                'error' => $error,
            ]);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function remiseAZeroEnseignements() {
        if (isset($_SESSION['mail'])) {
            $interventions = Intervention::where('mail_enseignant', 'like', $_SESSION['mail'])->get();

            foreach ($interventions as $intervention) {
                $intervention->heuresCM = 0;
                $intervention->heuresTD = 0;
                $intervention->heuresTP = 0;
                $intervention->heuresEI = 0;
                $intervention->groupeTD = 0;
                $intervention->groupeTP = 0;
                $intervention->groupeEI = 0;
                $intervention->save();
            }

            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

    public function ue(){
        if(isset($_SESSION['mail'])){
            $v = new VueUtilisateur();
            echo $v->ue();
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function formation(){
        if(isset($_SESSION['mail'])) {
            $v = new VueUtilisateur();
            echo $v->formation();
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function enseignants(){
        if(isset($_SESSION['mail'])) {
            $v = new VueUtilisateur();
            echo $v->enseignant();
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

     public function journal(){
        if(isset($_SESSION['mail'])) {
            $v = new VueUtilisateur();
            echo $v->journal();
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function journal_action_notification(){
        if(isset($_SESSION['mail'])) {
            $val = Slim::getInstance()->request->post();
            $valider = $val['valider']==false ? false : filter_var($val['valider'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            $refuser = $val['refuser']==false ? false : filter_var($val['refuser'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);

            $notification = Notification::where('id_notification', '=', $id)
                          ->first();

            if(!empty($notification)){
                //     #beaucoup de vérifications pour éviter les cas particuliers
                if(!$valider  && $refuser && $notification->besoin_validation){
                    //echo $notification->id_notification . " refuse";
                    $notification->besoin_validation = false;
                    $notification->validation = false;
                    $notification->save();

                }elseif($valider && !$refuser && $notification->besoin_validation) {
                    //echo $notification->id_notification . " valide";
                    $notification->besoin_validation = false;
                    $notification->validation = true;
                    $notification->save();
                }

                if($notification->validation){
                    switch($notification->type_notification){
                    case "PPIL\models\NotificationInscription":
                        $notification_inscription = NotificationInscription::where('id_notification', '=', $notification->id_notification)
                                                 ->first();

                        if(!empty($notification_inscription)){
                            NotificationInscription::appliquer($notification_inscription, $notification);
                        }
                        break;

                    case "PPIL\models\NotificationIntervention":
                        $notification_intervention = NotificationIntervention::where('id_notification', '=', $notification->id_notification)
                            ->first();
                        if(!empty($notification_intervention)){
                            NotificationIntervention::appliquer($notification_intervention, $notification);
                        }
                        break;

                    case "PPIL\models\NotificationResponsabilite":
                        $notification_responsabilite = NotificationResponsabilite::where('id_notification', '=', $notification->id_notification)
                            ->first();
                        if(!empty($notification_responsabilite)){
                            NotificationResponsabilite::appliquer($notification_responsabilite, $notification);
                        }
                        break;

                    default:
                        break;
                    }
                }else{
                    switch($notification->type_notification){
                        case "PPIL\models\NotificationInscription":
                            $notificationinscription = NotificationInscription::where('id_notification', '=', $notification->id_notification)
                                ->first();

                            if(!empty($notificationinscription)){
                                $mail = new MailControler();
                                $mail->sendMail($notificationinscription->mail,'Inscription','Votre inscription a été refusée par le responsable du département informatique.');
                            }
                            break;
                        default:
                            break;
                    }
                    if($notification->besoin_validation == false){
                        $notification_spe = $notification->type_notification::where('id_notification', '=', $notification->id_notification)
                                          ->first();
                        $notification_spe->delete();
                        $notification->delete();
                    }
                }
            }
        }
    }

    /////// Fonctions pour l'annuaire //////////

    public function annuaire(){
        if(isset($_SESSION['mail'])) {
            $users = Enseignant::distinct()->get();

            $v = new VueUtilisateur();
            echo $v->annuaire($users);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function rechercheAnnuaire() {
        if (isset($_SESSION['mail'])) {
            $app = Slim::getInstance();
            $val = $app->request->post();

            $chaine = filter_var($val['chaine'], FILTER_SANITIZE_STRING);

            $enseignants = \PPIL\models\Enseignant::distinct()->get();

            $res = array();

            $chaine = trim($chaine);

            $i = 0;
            foreach ($enseignants as $e) {
                if ((strpos(strtolower('' . $e->prenom . ' ' . $e->nom), strtolower($chaine)) !== FALSE || strpos(strtolower('' . $e->nom . ' ' . $e->prenom), strtolower($chaine)) !== FALSE || strpos(strtolower('' . $e->mail), strtolower($chaine)) !== FALSE) && $e->mail != ($_SESSION['mail'])) {
                    $res[$i][] = $e->prenom;
                    $res[$i][] = $e->nom;
                    $res[$i][] = $e->statut;
                    $res[$i][] = $e->mail;
                    $res[$i][] = $e->photo;
                    $i++;
                }
            }

            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode($res);
        } else {
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    public function annulerRecherche() {
        if(isset($_SESSION['mail'])) {
            $app = Slim::getInstance();
            $users = Enseignant::distinct()->get();

            $res = array();
            $i = 0;
            foreach ($users as $e) {
	    	    if($e->mail != $_SESSION['mail']){
                    		$res[$i][] = $e->prenom;
                		$res[$i][] = $e->nom;
                		$res[$i][] = $e->statut;
                		$res[$i][] = $e->mail;
                		$res[$i][] = $e->photo;
               			 $i++;
		}
            }

            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode($res);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

    ///////////////////////////////////////////


    public function inscription(){
        $val = Slim::getInstance()->request->post();

        $mail = filter_var($val['email'], FILTER_SANITIZE_STRING);

        $utilisateur = Enseignant::where('mail', 'like' , $mail) -> first();
        $newUtilisateur = NotificationInscription::where('mail','like',$mail)->first();
        if (empty($utilisateur) && empty($newUtilisateur)){ //l'utilisateur n'existe pas dans la BDD
            $mdp = filter_var($val['password'], FILTER_SANITIZE_STRING);
            $mdpConfirm = filter_var($val['password2'], FILTER_SANITIZE_STRING);

            if($mdp == $mdpConfirm){
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
                $prenom = filter_var($val['prenom'], FILTER_SANITIZE_STRING);
                $statut = filter_var($val['statut'], FILTER_SANITIZE_STRING);

                Enseignant::inscription($mail, $nom, $prenom, $statut, $mdp_hash);
                $v = new VueHome();
                echo $v->inscription(3);
            }else{
                $v = new VueHome();
                echo $v->inscription(1);
            }

        } else {
            $v = new VueHome();
            echo $v->inscription(2);
        }
    }

	public function reinitialiserMDP(){
		if(isset($_SESSION['mail'])) {
		    $val = Slim::getInstance()->request->post();
			$ancienMDP = filter_var($val['ancien'], FILTER_SANITIZE_STRING);
			$nveauMDP = filter_var($val['password'], FILTER_SANITIZE_STRING);
			$confirmMDP = filter_var($val['password2'], FILTER_SANITIZE_STRING);

			$ancien_hash = password_hash($ancienMDP, PASSWORD_DEFAULT);

			$utilisateur = Enseignant::where('mail', 'like', $_SESSION['mail'])->first();
			if ($ancien_hash == $utilisateur->mdp && $nveauMDP == $confirmMDP) {

				$nveauMDP_hash = password_hash($nveauMDP, PASSWORD_DEFAULT);

				Enseignant::reinitialiserMDP($utilisateur, $nveauMDP_hash);
			}

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
					Responsabilite::ajoutResponsabilite($responsable->mail, 'responsable ue', null, $nouvUE->id_UE);
				}
			}

		}
	}


	public function reinitialiserBDD(){
		if(isset($_SESSION['mail'])) {
			$resp = Responsabilite::where('intituleResp', 'like', 'Responsable du departement informatique')->first();

			Intervention::reinitialiserBDD();


			NotificationInscription::reinitialiserBDD();
			NotificationIntervention::reinitialiserBDD();
			NotificationResponsabilite::reinitialiserBDD();
			Notification::reinitialiserBDD();
			Responsabilite::reinitialiserBDD();
			UE::reinitialiserBDD();
			Formation::reinitialiserBDD();
			Enseignant::reinitialiserBDD($resp->enseignant);
			Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
		}
	}


	public function desinscription(){
		if(isset($_SESSION['mail'])) {

			Intervention::desinscription($_SESSION['mail']);
			$n = Notification::getNotification($_SESSION['mail']);
			foreach ($n as $notif){
				$n1 = NotificationInscription::where('id_notification', '=', $notif->id_notification)->first();
				if (!empty($n1)){
					$n1->delete();
				}
				$n2 = NotificationIntervention::where('id_notification', '=', $notif->id_notification)->first();
				if (!empty($n2)){
					$n2->delete();
				}
				$n3 = NotificationResponsabilite::where('id_notification', '=', $notif->id_notification)->first();
				if (!empty($n3)){
					$n3->delete();
				}
				$notif->delete();
			}

			Responsabilite::desinscription($_SESSION['mail']);
			Enseignant::desinscription($_SESSION['mail']);

			session_unset();
			Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
		}
	}


    public function deconnexion(){
        session_destroy();
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
    }
}