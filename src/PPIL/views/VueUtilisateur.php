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
use PPIL\models\Enseignant;
use PPIL\models\UE;
use PPIL\models\Formation;
use PPIL\models\Intervention;
use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use PPIL\models\NotificationIntervention;


class VueUtilisateur extends AbstractView
{
    public function home(){
        $html = self::headHTML();
        $html = $html . self::navHTML("Profil");
        $html = $html . self::footerHTML();
        return $html;
    }


  public function enseignement(){
      $lien = Slim::getInstance()->urlFor("enseignementUtilisateur.actionEnseignement");
      $lien_ajouter = Slim::getInstance()->urlFor("enseignementUtilisateur.actionEnseignementAjouter");
      $home = Slim::getInstance()->urlFor("home");

      $html = self::headHTML();
      $html = $html . self::navHTML("Enseignement");
      $notification_exist = false;
      $html .= <<< END
        <div class="container">
		  <div class="panel panel-default">
			<div class="panel-heading clearfix text-center">
			  <div class="btn-group pull-left">
			  	<button type="button" class="btn btn-default">Remise à zéro</button>
			  </div>
			  <div class="btn-group pull-right">
				<button type="button" class="btn btn-default" id="ajouter">Ajouter</button>
				<button type="button" class="btn btn-default">Exporter</button>
				<button type="button" class="btn btn-primary"  id="appliquer">Appliquer</button>
			  </div>
			 <h4>Fiche prévisionnelle Des enseignements</h4>
            </div>

            <div class="panel-body text-center">
      <div class="alert alert-danger hidden" role="alert" id="erreur">
      <strong>Echec!</strong> Vos données ne sont pas valides, vérifiez que vous n\'avez pas de nombres négatifs.
      </div>
      <div class="alert alert-warning hidden" role="alert" id="notification_exist">
      <strong>Attention !</strong> Certaines interventions attendent la validation de leur modification, aucun changement ne sera pris en compte entre temps.
      </div>
                  <div class="table-responsive ">

                  <table class="table table-bordered ">
                    <thead>
                      <tr>
                        <th class="text-center">Composante</th>
                        <th class="text-center">Formation</th>
                        <th class="text-center">UE</th>
                        <th class="text-center">Heures CM</th>
                        <th class="text-center">Heures TD</th>
                        <th class="text-center">Groupe TD</th>
                        <th class="text-center">Heures TP</th>
                        <th class="text-center">Groupe TP</th>
                        <th class="text-center">Heures EI</th>
                        <th class="text-center">Groupe EI</th>
						<th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
END;
        if(isset($_SESSION["mail"])){
            $e = Enseignant::where('mail', '=', $_SESSION["mail"])->first();
            $interventions = Intervention::where('mail_enseignant', '=', $e->mail)
                           ->get();
            $notifications = Notification::where('mail_destinataire', '=', $e->mail)
                           ->where('type_notification', '=', 'PPIL\models\NotificationIntervention')
                           ->get();

            $id_ues = array();
            $id_ues_notification = array();

            foreach($interventions as $intervention){
                $id_ues[] = $intervention->id_UE;
                $ue = UE::where("id_UE", "=", $intervention->id_UE)
                    ->first();
                $composante = $ue->fst==true ? 'FST' : 'Hors FST';
                $formation = Formation::where("id_formation", "=", $ue->id_formation)
                           ->first();

                $notification_en_attente = "";
                foreach($notifications as $notification){
                    $notification_intervention = NotificationIntervention::where('id_notification', '=', $notification->id_notification)
                                               //                                               ->where('id_UE', '=', $intervention->id_UE)
                                               ->get();

                    foreach($notification_intervention as $n){
                        if(!empty($n)){
                            if($n->id_UE == $intervention->id_UE){
                                $notification_en_attente = "warning";
                                $notification_exist = true;
                            }
                            $id_ues_notification[] = $n->id_UE ;
                        }
                    }
                }

                $html .= <<< END
					<tr id="$intervention->id_intervention" class="$notification_en_attente">

					  <td>$composante</td>
   					  <td>$formation->nomFormation</td>
					  <td>$ue->nom_UE</td>
                      <td >
						<input type="number" name="heuresCM" id="heuresCM" min="0" value="$intervention->heuresCM" class="form-control"/>
					  </td>
                      <td>
  						<input type="number" name="heuresTD" id="heuresTD" min="0" value="$intervention->heuresTD" class="form-control"/>
					  </td>
                      <td>
  						<input type="number" name="groupeTD" id="groupeTD" min="0" value="$intervention->groupeTD" class="form-control"/>
					  </td>
                      <td>
  						<input type="number" name="heuresTP" id="heuresTP" min="0" value="$intervention->heuresTP" class="form-control"/>
					  </td>
                      <td>
  						<input type="number" name="groupeTP" id="groupeTP" min="0" value="$intervention->groupeTP" class="form-control"/>
					  </td>
                      <td>
						<input type="number" name="heuresEI" id="heuresEI" min="0" value="$intervention->heuresEI" class="form-control"/>
					  </td>
                      <td>
						<input type="number" name="groupeEI" id="groupeEI" min="0" value="$intervention->groupeEI" class="form-control"/>
					  </td>
					  <td>
						<form class="form-inline" method="post" action="" id="form_interventions">
						  <div class="form-group">
							<button  name="annuler" class="btn btn-primary hidden" id="annuler" value="true" type="submit">Annuler</button>
							<button  name="supprimer" class="btn btn-default" id="supprimer" value="false" type="submit">Supprimer</button>
							<input type="hidden" id="id" name="id" value="$intervention->id_intervention" />
							<input type="hidden" id="id_UE" name="id_UE" value="$intervention->id_UE" />
						  </div>
						</form>

					  </td>
</tr>
END;
            }

        $html .= <<< END
        </tbody>
        </table>
        </div>
        </div>
        </div>
        </div>
        <div class="modal fade" id="modalDemandeEffectuee" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h4 class="modal-title">Modifications</h4>
			  </div>
			  <div class="modal-body">
				<p>Vos demandes ont été envoyées aux responsables de UE concernées.</p>
			  </div>
			  <div class="modal-footer">

				<button type="button" class="btn btn-default" onclick="location.href='$home'" data-dismiss="modal">Ok</button>
			  </div>
			</div>
		  </div>
		</div>

		<div class="modal fade text-center" id="modalAjouter" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Ajouter une intervention</h4>
              </div>
			  <div class="modal-body">
              <div class="table-responsive">
                  <table class="table table-bordered ">
                    <thead>
                      <tr>
                        <th class="text-center">Composante</th>
                        <th class="text-center">Formation</th>
                        <th class="text-center">UE</th>
						<th class="text-center">Sélectionner</th>
                      </tr>
                    </thead>
                    <tbody>

END;

        $ues = UE::whereNotIn('id_UE', $id_ues)
             ->whereNotIn('id_UE', $id_ues_notification)
             ->get();
        foreach($notifications as $notification){
            $notification_intervention = NotificationIntervention::where('id_notification', '=', $notification->id_notification)
                                       ->where('id_UE', '=', $intervention->id_UE)
                                       ->first();
            if(!empty($notification_intervention)){
                $notification_en_attente = "warning";
                $notification_exist = true;
            }
        }

        foreach($ues as $ue_ajout){
            $composante = $ue_ajout->fst==true ? 'FST' : 'Hors FST';
            $formation = Formation::where("id_formation", "=", $ue_ajout->id_formation)
                       ->first();

            $html .= <<< END
			   		  <tr>
						<td>$composante</td>
   						<td>$formation->nomFormation</td>
						<td>$ue_ajout->nom_UE</td>
						<td>
						  <form class="form-inline" method="post" action="" id="form_ajout_ue">
							<div class="form-group">
							  <button  name="selectionner" class="btn btn-primary" id="selectionner" value="false" type="submit">Sélectionner</button>
							  <button  name="annuler" class="btn btn-primary hidden" id="annuler" value="false" type="submit">Annuler</button>
							  <input type="hidden" id="id_UE" name="id_UE" value="$ue_ajout->id_UE" />
							</div>
						  </form>
						</td>
                     </tr>
END;
        }
        $html .= <<< END
                    </tbody>
                </table>
              </div>
			  <div class="form-group">
				<button type="button" class="btn btn-default"  id="modal_ajout_autre">Autre</button>
			  </div>
			  <form class="form-vertical hidden" id="form_ajout_autre">
				<div class="form-group">
				  <label class="control-label" for="ajout_autre_composante">Composante :</label>
				  <select class="form-control" id="ajout_autre_composante">
					<option>FST</option>
					<option>Hors FST</option>
				  </select>
				</div>
				<div class="form-group">
				  <label class="control-label" for="ajout_autre_formation">Formation :</label>
				  <input type="text" class="form-control" id="ajout_autre_formation">
				</div>
				<div class="form-group">
				  <label class="control-label" for="ajout_autre_ue">UE :</label>
				  <input type="text" class="form-control" id="ajout_autre_ue">
				</div>
			  </form>

			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default"  id="modal_demande">Effectuer la demande</button>
			  </div>
			</div>
		  </div>
		</div>

        <script type="text/javascript" src="/PPIL/assets/js/interventions.js"></script>
        <script type="text/javascript">
          $(function(){
		  ajouter("$lien_ajouter");
          valider("$lien", $notification_exist);
			});
        </script>


END;
        }
        $html = $html . self::footerHTML();
        return $html;
  }

    public function ue(){
        $html = self::headHTML();
        $html = $html . self::navHTML("UE");
        $html = $html . self::footerHTML();
        return $html;
    }


    public function enseignant(){
        $html = self::headHTML();
        $html = $html . self::navHTML("Enseignants");
        $html = $html . self::footerHTML();
        return $html;
    }

    public function journal(){
        $html = self::headHTML();
        $html = $html . self::navHTML("Journal");
        $html .= <<< END
	    <div class="container">
		  <div class="panel panel-default">
			<div class="panel-heading clearfix text-center">
			  <div class="btn-group pull-right">
				<button type="button" class="btn btn-default" disabled="true" id="appliquer">Appliquer</button>
			  </div>
			  <h4>Journal des modifications</h4>
			</div>
			<div class="panel-body text-center">
			<div class="table-responsive">
			  <table class="table table-bordered">
				<thead>
				  <tr>
					<th class="text-center">Enseignant</th>
					<th class="text-center">Description</th>
					<th class="text-center">Date</th>
					<th class="text-center">Actions</th>
				  </tr>
				</thead>
				<tbody>
END;
        if(isset($_SESSION["mail"])){
            $e = Enseignant::where('mail', '=', $_SESSION["mail"])->first();
            $notifications = Notification::where('mail_destinataire', '=', $e->mail)
                           ->get();

            foreach($notifications as $notification){
                $date = date('d/m/Y', strtotime($notification->created_at));
                $description = array($notification->message);
                $nom_source = "";
                $prenom_source = "";
                $lien = Slim::getInstance()->urlFor("JournalUtilisateur.actionNotification");
                switch($notification->type_notification){
                case "PPIL\models\NotificationInscription":
                    $notificationinscription = NotificationInscription::where('id_notification', '=', $notification->id_notification)
                                             ->first();
                    if(!empty($notificationinscription)){
                        $nom_source = $notificationinscription->nom;
                        $prenom_source = $notificationinscription->prenom;
                    }
                    break;
                case "PPIL\models\Notification":
                    $nom_source = $notification->mail_source;
                    break;
                default:
                    $enseignant_source = Enseignant::where('mail', '=',$notification->mail_source)->first();
                    if(!empty($enseignant_source)){
                        $nom_source = $enseignant_source->nom;
                        $prenom_source = $enseignant_source->prenom;
                    }
                    break;
                }

                $html .= <<< END
				<tr id="$notification->id_notification">
				  <td>$nom_source $prenom_source</td>
				  <td>
END;
                foreach($description as $item){
                    $html .= "<p>" . $item ."</p>";
                }
                $html .= <<< END
				  </td>
				  <td>$date</td>
                  <td>
END;
                $hide_annule = "";
                $hide_valide = "hidden";
                if($notification->besoin_validation == true){
                    $hide_annule = 'hidden';
                    $hide_valide = "";
                }
                $html .= <<< END
					<form class="form-inline" method="post" action="" id="form_actions">
					  <div class="form-group">
						<div id="annulation" class="$hide_annule">
						  <button  name="annuler" class="btn btn-primary" id="annule" value="true" type="submit">Annuler</button>
						</div>
						<div id="validation" class="$hide_valide">
						  <button  name="valider" class="btn btn-default" id="refuse" value="false" type="submit">Refuser</button>
						  <button  name="valider" class="btn btn-primary" id="valide" value="false" type="submit">Accepter</button>
						</div>

						<input type="hidden" id="id" name="id" value="$notification->id_notification" />

					  </div>
					</form>
				  </td>
				</tr>
END;
            }
        }

        $html .= <<< END
		    </tbody>
          </table>
        </div>
      </div>
  </div>
</div>
<script type="text/javascript" src="/PPIL/assets/js/cleanup.js"></script>

END;

        $html = $html . self::footerHTML();

        return $html;
    }

    public function annuaire($users){
        $html = self::headHTML();
        $html = $html . self::navHTML("Annuaire");
        $html .= <<< END
        <div class="container">
		  <div class="panel panel-default">
			<div class="panel-heading clearfix text-center">
			    <form class="navbar-form navbar-right inline-form">
                    <div class="form-group">
                      <input type="search" class="input-sm form-control" placeholder="Recherche">
                      <button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon"></span>Chercher</button>
                    </div>
                </form>
                <h4>Annuaire</h4>
			</div>
			<div class="panel-body text-center">
			<div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th class="text-center">Enseignant</th>
                        <th class="text-center">Statut</th>
                        <th class="text-center">Adresse Mail</th>
                        <th class="text-center">Photo</th>
                      </tr>

END;
        foreach ($users as $user) {
            if ($user->prenom!="admin" && $user->nom!="admin" && $_SESSION['mail']!=$user->mail) {
                $html .= "<tr>" .
                        "<th class=\"text-center\">" . $user->prenom . " " . $user->nom . "</th>" .
                        "<th class=\"text-center\">" . $user->statut . "</th>" .
                        "<th class=\"text-center\">" . $user->mail . "</th>";
                if($user->photo == null){
                    $default = "/PPIL/assets/images/profil_pictures/default.jpg";
                    $html .= '<td class="center" ><img src="' . $default  .'" class="img-thumbnail" alt="Photo de profil" width="35" height="35"></td>';
                }else{
                    $html .= '<td class="center" ><img src=' . "/PPIL/" . $user->photo  .' class="img-thumbnail" alt="Photo de profil" width="35" height="35"></td>';
                }

                $html .= "</tr>";
            }
        }

        $html .= <<<END
                    </thead>
                    </table>
			  </div>
            </div>
        </div>

END;

        $html = $html . self::footerHTML();
        return $html;
    }

}
