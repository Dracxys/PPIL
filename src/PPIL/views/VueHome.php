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

class VueHome extends AbstractView
{
    public static function home(){
        $html = self::headHTML();
        $lien = Slim::getInstance()->urlFor("login");
        $html = $html . <<< END
        <div class="connexion">
            <form method="post" action="$lien">
                <input type="text" name="field1" placeholder="Adresse Mail" />
                <input type="password" name="field2" placeholder="Mot de passe" />
                <input  type="submit" value="Connexion" class="envoyer"/>
            </form>
        </div>
END;
        return $html;

    }
}