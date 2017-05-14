<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 13/05/2017
 * Time: 22:00
 */

namespace PPIL\controlers;


use PPIL\models\Enseignant;
use PPIL\views\VueUtilisateur;
use Slim\Slim;

class UtilisateurControler
{

    public function home(){
        $v = new VueUtilisateur();
        if(isset($_SESSION['mail'])){
            echo $v->home();
        }
    }


    public function journal(){
        $v = new VueUtilisateur();
        echo $v->journal();
    }

    public function inscription(){
        $val = Slim::getInstance()->request->post();

        $mail = filter_var($val['email'], FILTER_SANITIZE_STRING);

        $utilisateur = Enseignant::where('mail', 'like' , $mail) -> first();
        if (empty($utilisateur)){ //l'utilisateur n'existe pas dans la BDD
            $mdp = filter_var($val['password'], FILTER_SANITIZE_STRING);
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

            $nom = filter_var($val['nom'], FILTER_SANITIZE_STRING);
            $prenom = filter_var($val['prenom'], FILTER_SANITIZE_STRING);
            $statut = filter_var($val['statut'], FILTER_SANITIZE_STRING);

            Enseignant::inscription($mail, $nom, $prenom, $statut, $mdp_hash);

            /********************************** MESSAGE A L'UTILISATEUR L'INFORMANT QUE SA DEMANDE A ETE PRISE EN COMPTE ******************************/

        } else {
            /********************************** LOAD ERREUR : E-MAIL DEJA UTILISE ******************************/
        }
    }
}