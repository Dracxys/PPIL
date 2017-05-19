<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 13/05/2017
 * Time: 22:00
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\models\NotificationResponsabilite;
use PPIL\views\VueHome;
use PPIL\views\VueModifProfil;
use PPIL\views\VueUtilisateur;
use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use PPIL\models\NotificationIntervention;
use PPIL\models\Intervention;
use PPIL\models\UE;
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


    public function enseignement_action(){
        if(isset($_SESSION['mail'])){
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
            if(!($id_UE != null && $id_UE >= 0 && $id != null && $id >= 0)){
                $error = true;
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
                $i = Intervention::where('id_intervention', '=', $id)
                   ->first();
                if(!empty($i)){
                    if($i->id_intervention == $id
                       && $i->heuresCM == $infos['heuresCM']
                       && $i->heuresTP == $infos['heuresTP']
                       && $i->heuresTD == $infos['heuresTD']
                       && $i->heuresEI == $infos['heuresEI']
                       && $i->groupeTP == $infos['groupeTP']
                       && $i->groupeTD == $infos['groupeTD']
                       && $i->groupeEI == $infos['groupeEI']
                       && $i->mail_enseignant == $_SESSION['mail']
                       && $i->id_UE == $id_UE){
                        if($supprime){
                            $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
                            Enseignant::modifie_intervention($e, $id, $id_UE, $infos, $supprime, null, null);
                        }
                    } else {
                        $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
                        Enseignant::modifie_intervention($e, $id, $id_UE, $infos, $supprime, null, null);
                    }
                }
            }
            echo json_encode([
                'error' => $error,
                'notification_exist' => $notification_exist
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
                Enseignant::modifie_intervention($e, $id, $id_UE, $infos, $supprime, null, null);
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
                Enseignant::modifie_intervention($e, $id, $id_UE, $infos, $supprime, $nom_UE, $nom_formation);
            }
            echo json_encode([
                'error' => $error,
            ]);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
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
                    echo $notification->id_notification . " refuse";
                    $notification->besoin_validation = false;
                    $notification->validation = false;
                    $notification->save();

                }elseif($valider && !$refuser && $notification->besoin_validation) {
                    echo $notification->id_notification . " valide";
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
                                $mail->sendMaid($notificationinscription->mail,'Inscription','Votre inscription a été refusée par le responsable du département informatique.');
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


    public function annuaire(){
        if(isset($_SESSION['mail'])) {
            $users = Enseignant::distinct()->get();

            $v = new VueUtilisateur();
            echo $v->annuaire($users);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

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


    public function deconnexion(){
        session_destroy();
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
    }
}