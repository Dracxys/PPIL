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
        $res = array();
        if(!empty($for)){
            $ue = UE::where('id_formation','=',$for->id_formation)->get();
            if(!empty($ue)){
                foreach ($ue as $value){
                    $res[] = $value->id_UE;
                    $res[] = $value->nom_UE;
                }
            }
        }
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($res);
    }

    public function infoUE(){
        $app = Slim::getInstance();
        $val = $app->request->post();
        $id = filter_var($val['id'],FILTER_SANITIZE_STRING);
        $ue = UE::where('id_UE','=',$id)->first();
        $app->response->headers->set('Content-Type', 'application/json');
        if(empty($ue)){
            $res = array();
            echo json_encode($res);
        }else{
            echo json_encode($ue);
        }

    }

    public function total(){
        $app = Slim::getInstance();
        $nom = $app->request->post();
        $nom = filter_var($nom['nom'],FILTER_SANITIZE_STRING);
        $for = \PPIL\models\Formation::where('nomFormation','like',$nom)->first();
        $res = array();
        if(!empty($for)){
            $ue = UE::where('id_formation','=',$for->id_formation)->get();
            if(!empty($ue)){
                $cmPrev = 0;
                $cm = 0;
                $tdPrev = 0;
                $td = 0;
                $tpPrev = 0;
                $tp = 0;
                $eiPrev = 0;
                $ei = 0;
                foreach ($ue as $value){
                    $cmPrev = $cmPrev + $value->prevision_heuresCM;
                    $cm = $cm + $value->heuresCM;
                    $tdPrev = $tdPrev + $value->prevision_heuresTD;
                    $td = $td + $value->heuresTD;
                    $tpPrev = $tpPrev + $value->prevision_heuresTP;
                    $tp = $tp + $value->heuresTP;
                    $eiPrev = $eiPrev + $value->prevision_heuresEI;
                    $ei = $ei +$value->heuresEI;
                }
                $res[] = $cmPrev;
                $res[] = $cm;
                $res[] = $tdPrev;
                $res[] = $td;
                $res[] = $tpPrev;
                $res[] = $tp;
                $res[] = $eiPrev;
                $res[] = $ei;
            }
        }
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($res);
    }


}