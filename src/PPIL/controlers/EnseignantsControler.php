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
use Slim\Slim;

class EnseignantsControler {

    public function home(){
        $u = Enseignant::all();
        $val = array();
        foreach ($u as $value){
            if(!in_array($value->mail,$val)){
                $val[] = $value->mail;
            }
        }

        if(isset($_SESSION['mail'])) {
            $v = new VueEnseignants();
            echo $v->home($val);
        }else{
            Slim::getInstance()->redirect(Slim::getInstance()->urlFor('home'));
        }
    }

}