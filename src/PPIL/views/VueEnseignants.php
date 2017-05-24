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
use PPIL\models\UE;
use PPIL\models\Responsabilite;

class VueEnseignants extends AbstractView{
    public function home($u){
        $scripts_and_css = "";
        $html  = self::headHTML($scripts_and_css);
        $html .= self::navHTML("Enseignants");
		$ajouter = Slim::getInstance()->urlFor("vueinscriptionDI");
		$lien_exporter = Slim::getInstance()->urlFor("enseignants.exporter");
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
                    <button type="sumbit" class="btn btn-primary navbar-btn" formaction="$ajouter"  formnovalidate="false" id="ajouterEnseignants">Ajouter</button>
                    <button type="button" class="btn btn-default navbar-btn" id="exporter">Exporter</button>
                  </div>
				</div>
				</form>
			  </div>
			</div>
			<div class="panel-body text-center">
			<div class="table-responsive">
END;

        if (count($u) == 1) {
            $html .= "<label>Aucun enseignant</label>";
        } else {
            $html .= <<<END
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center">Nom</th>
                    <th class="text-center">Statut</th>
                    <th class="text-center">Volume statutaire</th>
                    <th class="text-center">Service réalisé</th>
                    <th class="text-center">Service réalisé à la FST</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>

END;

            $i=0;
            foreach ($u as $user) {
                if ($_SESSION['mail']!=$user->mail) {
                    if ($user->volumeCourant == NULL) {
                        $volumeCourant = 0;
                    } else {
                        $volumeCourant = $user->volumeCourant;
                    }
                    $volFST = self::getVolumeFST($user);
                    
                    
                    $html .= "<tr name=\"ligne\" id=\"" . $i . "\" onclick=\"select(" . $i . ")\">" .
                        "<th class=\"text-center\">" . $user->prenom . " " . $user->nom . "</th>" .
                        "<th class=\"text-center\">" . $user->statut . "</th>" .
                        "<th class=\"text-center\" name=\"volMin\" id=\"volMin" . $i . "\">" . $user->volumeMin . "</th>";
                        
                        if($volumeCourant >= $user->volumeMin) {
                            $html.= "<th class=\"text-center\" name=\"volCourant\" id=\"volCourant" . $i . "\"><font color=\"green\">" . $volumeCourant . "</font></th>";
                        } else {
                            $html.= "<th class=\"text-center\" name=\"volCourant\" id=\"volCourant" . $i . "\"><font color=\"red\">" . $volumeCourant . "</font></th>";
                        }

                        if($volFST >= $user->volumeMin) {
                            $html.= "<th class=\"text-center\" name=\"volFST\" id=\"volFST" . $i . "\"><font color=\"green\">" . $volFST . "</font></th>";
                        } else {
                            $html.= "<th class=\"text-center\" name=\"volFST\" id=\"volFST" . $i . "\"><font color=\"red\">" . $volFST . "</font></th>";
                        }


                        $html .= "<th class=\"text-center\">" . "<button type='button' class='btn btn-default' onclick=location.href='" . Slim::getInstance()->urlFor('profilEnseignant', array('id' => $user->rand)) . "'>Voir</button> " . "</th>" .

                        "</tr>";
                    $i++;
                }
            }
            $html .= "</tbody></table>";

      }
      $html .= <<<END
        </div>
      </div>
  </div>
</div>
</div>


END;
        $html .= self::footerHTML();
        $html .=<<< END
              <script type="text/javascript" src="/PPIL/assets/js/enseignants.js"></script>
              <script type="text/javascript">
        $(function(){
            exporter("$lien_exporter");
        });
              </script>
END;
        return $html;
    }

	public function inscriptionParDI($num){
        $scripts_and_css = "";
		$html = self::headHTML($scripts_and_css);
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
END;
        if ($num == 2){
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

    public function profilEnseignant($enseignant) {
        $annuler = Slim::getInstance()->urlFor("enseignantsUtilisateur");
        $resp = self::recupResponsabilites($enseignant);
        $scripts_and_css = "";
        $html = self::headHTML($scripts_and_css);
        $html .= self::navHTML("Enseignants");
        $html .= <<<END

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
					Profil
				  </h4>
				</div>
				<form class="form-signin form-horizontal" method="post">
				<div class="collapse navbar-collapse" id="navbar_panel">
				  <div class="navbar-right">
END;
        $html .= "<button type='button' class='btn btn-danger' onclick=location.href='".Slim::getInstance()->urlFor('supprimerEnseignant',array('id' => $enseignant->rand))."'>Supprimer</button> " .
                    "<button type='submit' class='btn btn-default navbar-btn' formaction='$annuler' id='retourProfil'>Retour</button>" .
                  "</div>".
				"</div>".
				"</form>".
			  "</div>".
			"</div>";

        $html .= <<<END

        <div class="panel-body">
            <div class="row">
                <div class="col-md-7 text-center">
                    <form class="form-signin form-horizontal">
                      <div class="form-group">
                        <label class="control-label col-sm-4" for="nom">Nom </label>
                        <div class="col-sm-7">
                          <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" disabled="true" value="$enseignant->nom" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-sm-4" for="prenom">Prénom </label>
                        <div class="col-sm-7">
                          <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Prénom" disabled="true" value="$enseignant->prenom" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-sm-4" for="mail">Mail </label>
                        <div class="col-sm-7">
                          <input type="text" id="mail" name="mail" class="form-control" placeholder="Mail" disabled="true" value="$enseignant->mail" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-sm-4" for="statut">Statut </label>
                        <div class="col-sm-7">
                          <input type="text" id="statut" name="statut" class="form-control" placeholder="Statut" disabled="true" value="$enseignant->statut" />
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-sm-4" for="resp">Responsabilité(s) : </label>
                        <div class="col-sm-7">
                            $resp
                        </div>
                      </div>
                    </form>
                </div>

                <div class="col-md-5 text-center">
                    <div class="text-center svg-container">

END;

                        if($enseignant->photo == null) {
                            $default = "/PPIL/assets/images/profil_pictures/default.jpg";
                            $html .= '<img src=' . $default  .' class="img-thumbnail" alt="Photo de profil" width="296" height="220">';
                        } else {
                            $html .= '<img src=' . "/PPIL/" . $enseignant->photo  .' class="img-thumbnail" alt="Photo de profil" width="296" height="220">';
                        }

                        $html .= <<< END
                    </div>
                </div>
            </div>
        </div>
END;


        return $html;
    }


    public function recupResponsabilites($enseignant) {
        $responsabilites = Responsabilite::where('enseignant', '=', $enseignant->mail)->get();

        if(sizeof($responsabilites) > 0) {
            $html = '<select class="form-control" id="selectForm" name="selectForm">';
            foreach ($responsabilites as $resp) {
                if ($resp->id_formation != null) {
                    $formation = Formation::where('id_formation', '=', $resp->id_formation)->first();
                    $respIntitule = $resp->intituleResp . " pour " . $formation->nomFormation;
                    $html .= '<option value=' . '"' . $respIntitule . '"' . '>' . $respIntitule . '</option>';
                } else {
                    if ($resp->id_UE != null) {
                        $ue = UE::where('id_UE', '=', $resp->id_UE)->first();
                        $respIntitule = $resp->intituleResp . " pour " . $ue->nom_UE;
                        $html .= '<option value=' . '"' . $respIntitule . '"' . '>' . $respIntitule . '</option>';
                    }
                }
            }
        } else {
            $html = '<input type="text" id="statut" name="statut" class="form-control" placeholder="Responsabilite" disabled="true" value="Pas de responsabilité" />';
        }


        $html .= "</select>";

        return $html;
    }

    public function getVolumeFST($user)
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
