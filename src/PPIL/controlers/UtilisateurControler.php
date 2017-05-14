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
            # quand false, fait une variable vide..... http://stackoverflow.com/questions/9132274/php-validation-booleans-using-filter-var
            $valider = filter_var($val['valider'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $annuler = filter_var($val['annuler'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $id = filter_var($val['id'], FILTER_SANITIZE_NUMBER_INT);
            $notification = Notification::where('id_notification', '=', $id)
                          ->first();
            if($valider == null){
                $valider == false;
            }
            if($annuler == null){
                $annuler == false;
            }

            if(!empty($notification)){
                #beaucoup de vérifications pour éviter les cas particuliers
                if($annuler == false && $notification->besoin_validation == true ){
                    $notification->besoin_validation = false;
                    $notification->validation = $valider;
                } elseif($valider == false && $annuler == true && $notification->besoin_validation == false) {
                    $notification->besoin_validation = true;
                    $notification->validation = true;
                }
                $notification->save();
                $v = new VueUtilisateur();
                echo $v->journal();
            }
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
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