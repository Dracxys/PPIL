<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 16/05/2017
 * Time: 11:27
 */

namespace PPIL\controlers;


use PPIL\models\Formation;
use PPIL\models\UE;
use PPIL\views\VueFormation;
use Slim\Slim;

class FormationControler
{
    public function home(){
        $f = Formation::all();
        $val = array();
        foreach ($f as $value){
            if(!in_array($value->nomFormation,$val)){
                $val[] = $value->nomFormation;
            }
        }
        $v = new VueFormation();
        echo $v->home($val);
    }

    public function infoForm(){
        $app = Slim::getInstance();
        $nom = $app->request->post();
        $nom = filter_var($nom['nom'],FILTER_SANITIZE_STRING);
        $for = \PPIL\models\Formation::where('nomFormation','like',$nom)->first();
        $ue = UE::where('id_formation','=',$for->id_formation)->get();
        $res = array();
        if(!empty($ue)){
            foreach ($ue as $value){
                $res[] = $value->nom_UE;
            }
        }
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($res);
    }


}