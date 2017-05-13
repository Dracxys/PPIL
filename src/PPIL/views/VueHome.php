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
        <div class="container">
        <form method="post" action="$lien" id="connexion">
            <div class="form-group">
                <label for="email">Adresse Email :</label>
                <input type="email" id="fieldEmail" class="form-control" placeholder="Adresse Mail" />
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="fieldPassword" class="form-control" placeholder="Mot de passe" />
            </div>
                <button type="submit" class="btn btn-default">Connexion</button>
            </form>
        </div>
        </div>
END;
        return $html;

    }
}