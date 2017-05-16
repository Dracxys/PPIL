<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 16/05/2017
 * Time: 11:27
 */

namespace PPIL\controlers;


use PPIL\models\Formation;
use PPIL\views\VueFormation;

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


}