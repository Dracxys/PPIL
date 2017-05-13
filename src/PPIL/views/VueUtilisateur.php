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
    public static function home($e){
        $html = self::headHTML();
        $html = $html . self::navHTML();
        $html = $html . "Bonjour " . $e->prenom . " " . $e->nom;
        return $html;

    }
}
