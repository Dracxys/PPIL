<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 13/05/2017
 * Time: 10:12
 */

namespace PPIL\views;


use Slim\App;
use Slim\Slim;

class VueUtilisateur extends AbstractView
{
    public function home(){
        $html = self::headHTML();
        $html = $html . self::navHTML("Profil");
        return $html;
    }

    public function journal(){
        $html = self::headHTML();
        $html = $html . self::navHTML("Journal");
        $html .= "journal";
        return $html;
    }
}
