<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 17/05/2017
 * Time: 15:37
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\models\Intervention;
use PPIL\models\Notification;
use PPIL\models\NotificationIntervention;
use PPIL\models\NotificationResponsabilite;
use PPIL\models\Responsabilite;
use PPIL\views\VueEnseignants;
use PPIL\models\UE;
use PPIL\models\NotificationInscription;
use Slim\Slim;

class EnseignantsControler {

    public function home(){
        $u = Enseignant::all();
        $val = array();
        foreach ($u as $value){
            if(!in_array($value->nom,$val)){
                $val[] = $value;
            }
        }

        if(isset($_SESSION['mail'])) {
            $v = new VueEnseignants();
            echo $v->home($val);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }
	
	public function lancerVueInscriptionParDI(){
		$v = new VueEnseignants();
		echo $v->inscriptionParDI(0);
	}
	
	public function inscriptionParDI(){
		$val = Slim::getInstance()->request->post();

        $mail = filter_var($val['email'], FILTER_SANITIZE_STRING);

        $utilisateur = Enseignant::where('mail', 'like' , $mail) -> first();
        $newUtilisateur = NotificationInscription::where('mail','like',$mail)->first();
        if (empty($utilisateur) && empty($newUtilisateur)){ //l'utilisateur n'existe pas dans la BDD
                $mdp = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 10);
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
                $prenom = filter_var($val['prenom'], FILTER_SANITIZE_STRING);
                $statut = filter_var($val['statut'], FILTER_SANITIZE_STRING);

                Enseignant::inscriptionParDI($mail, $nom, $prenom, $statut, $mdp_hash);
                $mailControler = new MailControler();
                $mailControler->sendMail($mail, 'Inscription Service Enseignant', 'Le Responsable du Département Informatique a crée un compte Enseignant avec cette adresse mail. Voici le mot de passe associé à ce compte : ' . $mdp);

				$v = new VueEnseignants();
				echo $v->inscriptionParDI(3);

        }else{
			$v = new VueEnseignants();
			echo $v->inscriptionParDI(2);
		}
	}

	public function supprimer($id){
	    $id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);
	    $e = Enseignant::where('rand','=',$id)->first();
	    if(!empty($e)){
	        $mail = $e->mail;
	        $resp = Responsabilite::where('enseignant','like',$mail)->get();
	        foreach ($resp as $value){
	            $value->delete();
            }
            $inter = Intervention::where('mail_enseignant','like',$mail)->get();
            foreach ($inter as $value){
                $value->delete();
            }
            $notifDes = Notification::where('mail_destinataire','like',$mail)->get();
            foreach ($notifDes as $value){
                switch ($value->type_notification){
                    case "PPIL\models\NotificationIntervention":
                        $n = NotificationIntervention::where('id_notification','=',$value->id_notification)->first();
                        $n->delete();
                        $value->delete();
                        break;

                    case "PPIL\models\NotificationResponsabilite":
                        $n = NotificationResponsabilite::where('id_notification','=',$value->id_notification)->first();
                        $n->delete();
                        $value->delete();
                        break;
                }
            }
            $notifSour = Notification::where('mail_source','like',$mail)->get();
            foreach ($notifSour as $value){
                switch ($value->type_notification){
                    case "PPIL\models\NotificationIntervention":
                        $n = NotificationIntervention::where('id_notification','=',$value->id_notification)->first();
                        $n->delete();
                        $value->delete();
                        break;

                    case "PPIL\models\NotificationResponsabilite":
                        $n = NotificationResponsabilite::where('id_notification','=',$value->id_notification)->first();
                        $n->delete();
                        $value->delete();
                        break;
                }
            }
            $c = new MailControler();
            $c->sendMail($mail,"Suppression de votre compte Enseignant","Votre compte enseignant a été supprimé de l'application Service Enseignant par le Responsable du Département Informatique.");
            $e->delete();
            self::home();

        }
    }

}