<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 17/05/2017
 * Time: 15:33
 */
namespace PPIL\views;

use PPIL\models\Intervention;
use Slim\App;
use Slim\Slim;
use PPIL\models\Enseignant;
use PPIL\models\Formation;
use PPIL\models\Responsabilite;

class VueEnseignants extends AbstractView{
    public function home($u){
        $html  = self::headHTML();
        $html .= self::navHTML("Enseignants");
		$ajouter = Slim::getInstance()->urlFor("vueinscriptionDI");
        $html .= <<< END

        <div class="container">
		  <div class="panel panel-default">
			<div class="panel-heading nav navbar-default">
			  <div class="container-fluid">

				<div class="navbar-header">
				  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar_panel">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <h4 class="navbar-text">
					Enseignants
				  </h4>
				</div>
				<form class="form-signin form-horizontal" method="post">
				<div class="collapse navbar-collapse" id="navbar_panel">
				  <div class="navbar-right">
                                       <button type="submit" class="btn btn-default" formaction="$ajouter"  formnovalidate="false" id="ajouterEnseignants">Ajouter</button>
                                        <button type="button" class="btn btn-default navbar-btn" id="exporterEnseignants">Exporter</button>
                                    </div>
				</div>
				</form>
			  </div>
			</div>
			<div class="panel-body text-center">
			<div class="table-responsive">
			  <table class="table table-bordered">
				<thead>
				  <tr>
					<th class="text-center">Nom</th>
					<th class="text-center">Statut</th>
					<th class="text-center">Volume statutaire</th>
					<th class="text-center">Service réalisé</th>
					<th class="text-center">Service réalisé à la FST</th>
				  </tr>
                
                
END;

        $i=0;
        foreach ($u as $user) {
            if ($_SESSION['mail']!=$user->mail) {
                if($user->volumeCourant==NULL) {
                    $volumeCourant=0;
                } else {
                    $volumeCourant=$user->volumeCourant;
                }
                $volFST = self::getVolumeFST($user);
                //$html .= "<tr id=\"ligne".$i."\" onclick=\"select(this)\">" .
                $html .= "<tr name=\"ligne\" id=\"".$i."\" onclick=\"select(".$i.")\">" .
                    "<th class=\"text-center\">" . $user->prenom . " " . $user->nom . "</th>" .
                    "<th class=\"text-center\">" . $user->statut . "</th>" .
                    "<th class=\"text-center\">" . $user->volumeMin . "</th>" .
                    "<th class=\"text-center\">" . $volumeCourant . "</th>" .
                    "<th class=\"text-center\">" . $volFST . "</th>" .

                    "</tr>";
                $i++;
            }
        }
        $html .= <<< END

				</thead>
				<tbody>
			    </tbody>
          </table>
        </div>
      </div>
  </div>
</div>
</div>


END;
        $html .= self::footerHTML();
        $html .= "<script type=\"text/javascript\" src=\"/PPIL/assets/js/enseignants.js\">     </script>";
        return $html;
    }

	public static function inscriptionParDI($num){
		$html = self::headHTML();
		$html .= self::navHTML("Enseignants");
        $valider = Slim::getInstance()->urlFor("inscriptionParDI");
        $annuler = Slim::getInstance()->urlFor("enseignantsUtilisateur");
        $html = $html . <<< END
		<div class="container panel panel-default text-center">
		  <div class="panel-body">
			<form class="form-signin form-horizontal" method="post" action="$valider" id="inscription">
			  <h2 class="form-signin-heading ">Ajout d'un nouvel enseignant</h2>
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
            <div class="alert alert-danger">
                La confirmation de votre mot de passe est erronée.
            </div>
END;
        }elseif ($num == 2){
            $html = $html . <<< END
            <div class="alert alert-danger">
                Adresse mail déjà utilisée.
            </div>
END;
        }
		if ($num == 3){
            $html = $html . <<< END

            <div class="modal fade" id="modalDemandeEffectuee" role="dialog">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<h4 class="modal-title">Le nouvel enseignant a bien été ajouté.</h4>
				  
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
               valider();
			});
        </script>
END;
        }
        $html = $html . <<< END
        <div class="form-group">
				<button type="submit" class="btn btn-primary" formaction="$valider" id="button_valider">Valider</button>
				<button type="submit" class="btn btn-default" formaction="$annuler" formnovalidate="false">Annuler</input>
        </div>
		</form>

                </div>
            </div>
END;


$html = $html . self::footerHTML();

        return $html;
	}

    public static function getVolumeFST($user)
    {
        $intervention = Intervention::where('mail_enseignant', 'like', $user->mail)->where('fst','like','1')->get();
        $heuresTD = 0;
        $heuresCM = 0;
        $heuresTP = 0;
        $heuresEI = 0;
        $heuresTotales = 0;
        foreach ($intervention as $value){
            $heuresTD += $value->heuresTD;
            $heuresCM += $value->heuresCM;
            $heuresEI += $value->heuresEI;
            $heuresTP += $value->heuresTP;
        }
        if($user->statut == "Professeur des universités" || $user->statut == "Maître de conférences"){
            $heuresTotales = $heuresTD + ($heuresCM *(3/2)) + ($heuresEI)* (7/6) + ($heuresTP);
        }else{
            $heuresTotales = $heuresTD + ($heuresCM *(3/2)) + ($heuresEI* (7/6)) + ($heuresTP * (3/2));
        }
        return ceil($heuresTotales);

    }
}
