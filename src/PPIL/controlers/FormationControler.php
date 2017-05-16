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
        $v = new VueFormation();
        echo $v->home($f);
    }


}