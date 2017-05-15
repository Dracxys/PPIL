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
    public function home($num){
        $html = self::headHTML();
        $lien = Slim::getInstance()->urlFor("login");
        $lien_oublie = Slim::getInstance()->urlFor("oubliMDP");
        $lien_inscription = Slim::getInstance()->urlFor("inscription");
        $html = $html . <<< END
		<div class="container panel panel-default text-center">
		  <div class="panel-body">
			<form class="form-signin form-horizontal" method="post" action="$lien" id="connexion">
			  <h2 class="form-signin-heading ">Bienvenue</h2>
              <div class="form-group">
				<label class="control-label col-sm-4" for="email">Adresse Email :</label>
				<div class="col-sm-4">
				  <input type="email" id="email" name="email" class="form-control" placeholder="Adresse Mail" required="true"/>
				</div>
			  </div>
              <div class="form-group">
				<label class="control-label col-sm-4" for="password">Mot de passe :</label>
				<div class="col-sm-4">
				  <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" />
				</div>
			  </div>
END;
        if($num == 1){
            $html = $html . <<< END
            <div class="alert alert-warning">
                Votre adresse mail ou mot de passe est incorrect.
            </div>
END;
        }

        $html = $html . <<< END
              <div class="form-group">
				<button type="submit" class="btn btn-primary">Connexion</button>
              </div>

			  <div class="form-group">
				<button type="submit" class="btn btn-default" formaction="$lien_inscription"  formnovalidate="false">Inscription</button>
   			  </div>

              <div class="form-group">
				  <button type="submit" class="btn btn-default" formaction="$lien_oublie" formnovalidate="false">Mot de passe oublié ?</button>
			  </div>
			</form>
		  </div>
        </div>
END;

        $html = $html . self::footerHTML();
        return $html;

    }

    public function inscription($num = 0){
        $html = self::headHTML();
        $valider = Slim::getInstance()->urlFor("validerInscription");
        $annuler = Slim::getInstance()->urlFor("home");
        $html = $html . <<< END
		<div class="container panel panel-default text-center">
		  <div class="panel-body">
			<form class="form-signin form-horizontal" method="post" id="valider">
			  <h2 class="form-signin-heading ">Inscription</h2>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="nom">Nom </label>
				<div class="col-sm-4">
				  <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" required="true"/>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="prenom">Prénom </label>
				<div class="col-sm-4">
				  <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Prénom" required="true"/>
				</div>
			  </div>
              <div class="form-group">
				<label class="control-label col-sm-4" for="email">Adresse Mail </label>
				<div class="col-sm-4">
				  <input type="email" id="email" name="email" class="form-control" placeholder="Adresse Mail" required="true"/>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="statut">Statut </label>
				<div class="col-sm-4">
				  <select class="form-control" name="statut">
				    <option value="Professeur des universités">Professeur des universités</option>
				    <option value="Maître de conférences">Maître de conférences</option>
				    <option value="PRAG">PRAG</option>
				    <option value="ATER">ATER</option>
				    <option value="1/2 ATER">1/2 ATER</option>
				    <option value="Doctorant">Doctorant</option>
				    <option value="Vacataire">Vacataire</option>
				  </select>
				</div>
			  </div>
              <div class="form-group">
				<label class="control-label col-sm-4" for="password">Mot de passe </label>
				<div class="col-sm-4">
				  <input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" required="true"/>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="password">Confirmer mot de passe </label>
				<div class="col-sm-4">
				  <input type="password" id="password" name="password2" class="form-control" placeholder="Mot de passe" required="true"/>
				</div>
			  </div>
END;
        if($num == 1){
            $html = $html . <<< END
            <div class="alert alert-warning">
                La confirmation de votre mot de passe est erronée.
            </div>
END;
        }elseif ($num == 2){
            $html = $html . <<< END
            <div class="alert alert-warning">
                Adresse mail déjà utilisée.
            </div>
END;
        }
           $html = $html . <<< END
           <div class="form-group">
				<button type="submit" class="btn btn-primary" id="button_valider">Valider</button>
				<button type="submit" class="btn btn-default" formaction="$annuler" formnovalidate="false">Annuler</input>
              </div>
			</form>

            <div class="modal fade" id="modalDemandeEffectuee" role="dialog">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<h4 class="modal-title">Votre demande a été prise en compte.</h4>
				  </div>
				  <div class="modal-body">
					<p>Un mail vous sera envoyé quand le responsable aura validé ou refusé votre demande.</p>
				  </div>
				  <div class="modal-footer">

					<button type="button" class="btn btn-default" onclick="location.href='$annuler'" data-dismiss="modal">Ok</button>
				  </div>
				</div>
			  </div>
			</div>

		  </div>
        </div>
		<script type="text/javascript" src="/PPIL/assets/js/inscription.js">     </script>
        <script type="text/javascript">
           $(function(){
               valider("$valider");
			});
        </script>
END;

$html = $html . self::footerHTML();

        return $html;
    }

    public static function oubliMDP(){
        $html = self::headHTML();
        $valider = Slim::getInstance()->urlFor("home");
        $annuler = Slim::getInstance()->urlFor("home");
        $html = $html . <<< END
        <div class="container panel panel-default text-center">
		  <div class="panel-body">
			<form class="form-signin form-horizontal" method="post" action="$valider" id="valider">
			  <h2 class="form-signin-heading ">Oubli mot de passe</h2>
              <div class="form-group">
				<label class="control-label col-sm-4" for="email">Adresse Mail </label>
				<div class="col-sm-4">
				  <input type="email" id="email" name="email" class="form-control" placeholder="Adresse Mail" required="true"/>
				</div>
			  </div>
              <div class="form-group">
				<button type="submit" class="btn btn-primary">Réinitialiser mot de passe</button>
              </div>
              <div class="form-group">
				  <button type="submit" class="btn btn-default" formaction="$annuler" formnovalidate="false">Annuler</button>
			  </div>
			</form>
		  </div>
        </div>
END;

        $html = $html . self::footerHTML();
        return $html;
    }
}
