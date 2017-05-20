<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 17/05/2017
 * Time: 15:37
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
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
            $mdp = filter_var($val['password'], FILTER_SANITIZE_STRING);
            $mdpConfirm = filter_var($val['password2'], FILTER_SANITIZE_STRING);

            if($mdp == $mdpConfirm){
                $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
                $prenom = filter_var($val['prenom'], FILTER_SANITIZE_STRING);
                $statut = filter_var($val['statut'], FILTER_SANITIZE_STRING);

                Enseignant::inscriptionParDI($mail, $nom, $prenom, $statut, $mdp_hash);
				$v = new VueEnseignants();
				echo $v->inscriptionParDI(3);
            }else{
				$v = new VueEnseignants();
				echo $v->inscriptionParDI(1);
			}

        }else{
			$v = new VueEnseignants();
			echo $v->inscriptionParDI(2);
		}
	}

}