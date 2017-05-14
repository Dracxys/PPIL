<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 13/05/2017
 * Time: 22:00
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\views\VueHome;
use PPIL\views\VueUtilisateur;
use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use Slim\Slim;


class UtilisateurControler
{

    public function home(){
        $v = new VueUtilisateur();
        if(isset($_SESSION['mail'])){
            echo $v->home();
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
                        $notificationinscription = NotificationInscription::where('id_notification', '=', $notification->id_notification)
                                                 ->first();

                        if(!empty($notificationinscription)){
                            $e = new Enseignant();
                            $e->nom = $notificationinscription->nom;
                            $e->prenom = $notificationinscription->prenom;
                            $e->mail = $notificationinscription->mail;
                            $e->mdp = $notificationinscription->mot_de_passe;
                            $nom_source = $notificationinscription->nom;
                            $prenom_source = $notificationinscription->prenom;
                            $e->save();
                        }
                        break;
                    default:
                        break;
                    }
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

    public function inscription(){
        $val = Slim::getInstance()->request->post();

        $mail = filter_var($val['email'], FILTER_SANITIZE_STRING);

        $utilisateur = Enseignant::where('mail', 'like' , $mail) -> first();
        if (empty($utilisateur)){ //l'utilisateur n'existe pas dans la BDD
            $mdp = filter_var($val['password'], FILTER_SANITIZE_STRING);
            $mdpConfirm = filter_var($val['password2'], FILTER_SANITIZE_STRING);

            if($mdp == $mdpConfirm){
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
                $prenom = filter_var($val['prenom'], FILTER_SANITIZE_STRING);
                $statut = filter_var($val['statut'], FILTER_SANITIZE_STRING);

                Enseignant::inscription($mail, $nom, $prenom, $statut, $mdp_hash);

                /********************************** MESSAGE A L'UTILISATEUR L'INFORMANT QUE SA DEMANDE A ETE PRISE EN COMPTE ******************************/

            }else{
                $v = new VueHome();
                echo $v->inscription(1);
            }

        } else {
            $v = new VueHome();
            echo $v->inscription(2);
        }
    }
	
	public static function reinitialiserMDP(){
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
				
				/********************************** MESSAGE A L'UTILISATEUR L'INFORMANT QUE DU CHANGEMENT DE MOT DE PASSE ******************************/

			}
			
			/********************************** MESSAGE D'ERREUR ******************************/
				
		}
		
	}

    public function deconnexion(){
        session_destroy();
        Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
    }
}