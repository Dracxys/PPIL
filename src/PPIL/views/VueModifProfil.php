<?php

namespace PPIL\views;


use Slim\App;
use Slim\Slim;

class VueModifProfil extends AbstractView
{
    public function home(){
        $html = self::headHTML();
        $lien_valider_annuler = Slim::getInstance()->urlFor("home");
        $html = $html . self::navHTML("Profil");
        $html = $html . <<< END
		<div class="container panel panel-default text-center">
		  <div class="panel-body">
			<form class="form-signin form-horizontal" method="post"  id="valider">
			  <h2 class="form-signin-heading ">Modification du profil</h2>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="nom">Nom </label>
				<div class="col-sm-4">
				  <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" required="true" value="jean"/>
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
				<button type="submit" class="btn btn-primary">Valider</button>
				<button type="submit" class="btn btn-default" formnovalidate="false">Annuler</input>
              </div>
			</form>

END;
 $html = $html . self::footerHTML();

        return $html;
    }
}
