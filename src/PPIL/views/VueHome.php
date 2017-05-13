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
        $lien_oublie = "";
        $lien_inscription = "";
        $html = $html . <<< END
		<div class="container panel panel-default text-center">
		  <div class="panel-body">
			<form class="form-signin form-horizontal" method="post" action="$lien" id="connexion">
			  <h2 class="form-signin-heading ">Bienvenue</h2>
              <div class="form-group">
				<label class="control-label col-sm-4" for="email">Adresse Email :</label>
				<div class="col-sm-4">
				  <input type="text" id="email" name="email" class="form-control" placeholder="Adresse Mail" required="true"/>
				</div>
			  </div>
              <div class="form-group">
				<label class="control-label col-sm-4" for="password">Mot de passe :</label>
				<div class="col-sm-4">
				  <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" />
				</div>
			  </div>
			  <div class="form-group">
							  <button type="submit" class="btn btn-default" formaction="$lien_oublie">Mot de passe oublié ?</button>
			  </div>
			  <div class="form-group">
				<button type="submit" class="btn btn-primary">Connexion</button>
              </div>
			  <div class="form-group">
				<button type="submit" class="btn btn-default" formaction="$lien_inscription">Inscription</button>
				</div>
			</form>
		  </div>
        </div>
END;
        return $html;

    }
}
