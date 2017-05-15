<?php

namespace PPIL\views;


use PPIL\models\Enseignant;
use Slim\App;
use Slim\Slim;

class VueModifProfil extends AbstractView
{
    public static function home($user, $num){
        $select = self::selectStatut($user);
        $html = self::headHTML();
        $modifprofil = Slim::getInstance()->urlFor("modificationProfil");
        $modifpassword = Slim::getInstance()->urlFor("modificationPassword");
        $html = $html . self::navHTML("Profil");
        $html = $html . <<< END
		<div class="container panel panel-default text-center ">
		<div class="container">
		 <div class="list-group">
        <a href="#" id="boutonInfo" class="list-group-item active">Informations personnelles</a>
        <a href="#" id="boutonResp" class="list-group-item">Responsabilités</a>
         <a href="#" id="boutonPhoto" class="list-group-item">Photo</a>
          <a href="#" id="boutonPassword" class="list-group-item">Mot de passe</a>
END;
        if($num == 0){
            $html .= <<< END
            <div class="alert alert-success" role="alert">
                <strong>Succès!</strong> Modification de profil validé.
            </div>
END;
        }
        if($num == 1){
            $html .= <<< END
            <div class="alert alert-success" role="alert">
                <strong>Succès!</strong> Modification du mot de passe validé.
            </div>
END;
        }
        if($num == 2){
            $html .= <<< END
            <div class="alert alert-warning" role="alert">
                <strong>Echec!</strong> Ancien mot de passe non valide.
            </div>
END;
        }
        if($num == 3){
            $html .= <<< END
            <div class="alert alert-warning" role="alert">
                <strong>Echec!</strong> Nouveau mot de passe et la confirmation sont différents.
            </div>
END;
        }

		$html.= <<< END
        <div id="infoperso" class="panel-body">
			<form class="form-signin form-horizontal" method="post" action="$modifprofil"  id="valider">
			  <h2 class="form-signin-heading ">Modification du profil</h2>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="nom">Nom </label>
				<div class="col-sm-4">
				  <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" required="true" value=$user->nom />
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="prenom">Prénom </label>
				<div class="col-sm-4">
				  <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Prénom" required="true" value=$user->prenom />
				</div>
			  </div>
              <div class="form-group">
				<label class="control-label col-sm-4" for="email">Adresse Mail </label>
				<div class="col-sm-4">
				  <input type="email" id="email" name="email" class="form-control" placeholder="Adresse Mail" required="true" value=$user->mail />
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="statut">Statut </label>
				<div class="col-sm-4">
END;
            $html .= $select;
            $html .= <<< END
				</div>
			  </div>

			  <div class="form-group">
				<button type="submit" class="btn btn-primary">Valider</button>
				<button type="submit" class="btn btn-default" formnovalidate="false">Annuler</input>
              </div>
			</form>
            </div>
            <div id="responsabilite" style="display: none;">
                <p>Responsabilités</p>
            </div>
            <div id="photo" style="display: none;">
                <p>Photo</p>
            </div>
            <div id="motdepasse" style="display: none;">
                <form class="form-signin form-horizontal" method="post" action="$modifpassword"  id="valider">
			  <h2 class="form-signin-heading ">Modification du mot de passe</h2>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="ancien">Ancien mot de passe</label>
				<div class="col-sm-4">
				  <input type="password" id="ancien" name="ancien" class="form-control" placeholder="Ancien mot de passe" required="true"/>
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="nouv">Nouveau mot de passe</label>
				<div class="col-sm-4">
				  <input type="password" id="nouv" name="nouv" class="form-control" placeholder="Nouveau mot de passe" required="true" />
				</div>
			  </div>
              <div class="form-group">
				<label class="control-label col-sm-4" for="conf">Confirmer du nouveau mot de passe</label>
				<div class="col-sm-4">
				  <input type="password" id="conf" name="conf" class="form-control" placeholder="Confirmer nouveau mot de passe" required="true"/>
				</div>
			  </div>
				<button type="submit" class="btn btn-primary">Valider</button>
				<button type="submit" class="btn btn-default" formnovalidate="false">Annuler</input>
              </div>
			</form>
            </div>
            </div>

END;
 $html = $html . self::footerHTML();
        $html .= "		<script type=\"text/javascript\" src=\"/PPIL/assets/js/modifprofil.js\">     </script>";
        return $html;
    }

    public static function selectStatut($user){
    $array = array("Professeur des universités","Maître de conférences","PRAG","1/2 ATER","Doctorant","Vacataire");
        $html = '<select class="form-control" name="statut">';
        foreach ($array as $value){
            if($value == $user->statut){
                $html .= '<option selected value=' . $value .'>' . $value . '</option>';
            }else{
                $html .= '<option value=' . $value .'>' . $value . '</option>';
            }
        }
        $html .= "</select>";
        return $html;
    }
}
