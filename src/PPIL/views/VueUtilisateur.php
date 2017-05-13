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
    public static function home(){
        $html = self::headHTML();
        $html = $html . self::navHTML();
        $lien = Slim::getInstance()->urlFor("login");
        $lien_oublie = "";
        $lien_inscription = "";
        $html = $html . <<< END
END;
        return $html;

    }
}
