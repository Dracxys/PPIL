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
use PPIL\models\Responsabilite;
use PPIL\models\Notification;
use PPIL\models\NotificationInscription;
use PPIL\models\NotificationIntervention;


class VueUtilisateur extends AbstractView
{
    public function home(){
        $scripts_and_css = "";
        $html  = self::headHTML($scripts_and_css);
        $html = $html . self::navHTML("Profil");
        $html = $html . self::footerHTML();
        return $html;
    }


  public function enseignement(){
      $lien = Slim::getInstance()->urlFor("enseignementUtilisateur.actionEnseignement");
      $lien_exporter = Slim::getInstance()->urlFor("enseignementUtilisateur.exporter");
      $lien_ajouter = Slim::getInstance()->urlFor("enseignementUtilisateur.actionEnseignementAjouter");
      $lien_ajouter_autre = Slim::getInstance()->urlFor("enseignementUtilisateur.actionEnseignementAjouterAutre");
      $lien_remiseAZero = Slim::getInstance()->urlFor("enseignementUtilisateur.actionEnseignementRemiseAZero");
      $lien_enseignement = Slim::getInstance()->urlFor("enseignementUtilisateur");
      $home = Slim::getInstance()->urlFor("home");

      $scripts_and_css = "";
      $html  = self::headHTML($scripts_and_css);
      $html = $html . self::navHTML("Enseignement");
      $notification_exist = 0;
      $e = Enseignant::where('mail','like',$_SESSION['mail'])->first();
      $depasse = $e->volumeCourant - $e->volumeMax;

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
				  <h4 class="navbar-text hidden-xs hidden-sm">
					Fiche prévisionnelle des enseignements
				  </h4>
				  <h4 class="navbar-text hidden-md hidden-lg">
					Fiche prévisionnelle
				  </h4>
				</div>

				<div class="collapse navbar-collapse" id="navbar_panel">
					<div class="navbar-right">
					  <button type="button" class="btn btn-default navbar-btn" id="ajouter">Ajouter</button>
					  <button type="button" class="btn btn-default navbar-btn" id="exporter">Exporter</button>
					  <button type="button" class="btn btn-danger navbar-btn" id="remiseZero">Remise à zéro</button>
					  <button type="button" class="btn btn-primary navbar-btn"  id="appliquer">Appliquer</button>
					</div>
				</div>

			  </div>
			</div>
            <div class="panel-body text-center">
      <div class="alert alert-danger hidden" role="alert" id="erreur">
      <strong>Echec!</strong>Certaines informations sont invalides, vérifiez que vos données ne contiennent pas de nombres négatifs ou de caractères spéciaux
      </div>
      <div class="alert alert-danger hidden" role="alert" id="depassement_prevision">
      <strong>Attention !</strong> Vos modifications feraient dépasser les prévisions en heures et en groupe pour une ou plusieurs UE.
      </div>
      <div class="alert alert-warning hidden" role="alert" id="notification_exist">
      <strong>Attention !</strong> Certaines interventions attendent la validation de leur modification, aucun changement ne sera pris en compte entre temps.
      </div>
      <div class="alert alert-warning hidden" role="alert" id="depassement_max">
      <strong>Attention !</strong> Vous dépassez actuellement vos horaires maximaux.
      </div>
      <div class="alert alert-success hidden" role="alert" id="succes">
        <strong>Succès!</strong> Vos demandes ont été envoyées.
      </div>
      <div class="alert alert-success hidden" role="alert" id="succesRemiseAZero">
        <strong>Succès!</strong> La remise à zéro a bien été effectuée.
      </div>
      <div class="alert alert-success hidden" role="alert" id="succes_sans_demande">
        <strong>Succès!</strong> Vos changements ont été appliqués.
      </div>

                  <div class="table-responsive ">

                  <table class="table table-bordered ">
                    <thead>
                      <tr>
                        <th class="text-center">Composante</th>
                        <th class="text-center">Formation</th>
                        <th class="text-center">Intitulé</th>
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
            $notifications = Notification::where('mail_source', '=', $e->mail)
                           ->where('type_notification', '=', 'PPIL\models\NotificationIntervention')
                           ->get();

            $id_ues = array();
            $id_ues_notification = array();
            $id_interventions = array();

            foreach($interventions as $intervention){
                $id_interventions[] =  $intervention->id_intervention;

                $composante = "";
                $formation = "";
                $ue = "";
                $suppression = "";

                if($intervention->id_UE != null){
                    $id_ues[] = $intervention->id_UE;

                    $u = UE::where("id_UE", "=", $intervention->id_UE)
                       ->first();
                    $ue = $u->nom_UE;
                    $composante = $u->fst==true ? 'FST' : 'Hors FST';
                    $formation = Formation::where("id_formation", "=", $u->id_formation)
                               ->first()
                               ->nomFormation;
                } else if($intervention->id_responsabilite != null){
                    $resp = Responsabilite::find($intervention->id_responsabilite);
                    if($resp != null){
                        $suppression = "disabled";
                        $composante = $intervention->fst==true ? 'FST' : 'Hors FST';
                        if($resp->id_UE != null && $resp->privilege == 0){
                            $u = UE::where("id_UE", "=", $resp->id_UE)
                               ->first();
                            $ue = 'Responsable UE';

                            $formation = Formation::where("id_formation", "=", $u->id_formation)
                                       ->first()
                                       ->nomFormation;

                        } else if($resp->id_formation != null && $resp->privilege == 1){
                            $ue = 'Responsable Formation';

                            $formation = Formation::where("id_formation", "=", $resp->id_formation)
                                       ->first()
                                       ->nomFormation;

                        } else if($resp->privilege == 2){
                            $ue = 'Responsable Département';

                            $formation = "Toutes";
                        }
                    }
                }
                $notification_en_attente = "";
                foreach($notifications as $notification){
                    $notification_intervention = NotificationIntervention::where('id_notification', '=', $notification->id_notification)
                                               //                                               ->where('id_UE', '=', $intervention->id_UE)
                                               ->get();

                    foreach($notification_intervention as $n){
                        if(!empty($n)){
                            if($n->id_UE == $intervention->id_UE){
                                $notification_en_attente = "warning";
                                $notification_exist = 1;
                            }
                            $id_ues_notification[] = $n->id_UE ;
                        }
                    }
                }
                $html .= <<< END
					<tr id="$intervention->id_intervention" class="$notification_en_attente">

					  <td>$composante</td>
   					  <td>$formation</td>
					  <td>$ue</td>
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
							<button  name="supprimer" class="btn btn-danger $suppression" id="supprimer" value="false" type="submit">Supprimer</button>
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
             ->whereNotIn('fst', [false])
             ->get();
        if(!empty($id_intervention)){
            foreach($notifications as $notification){
                $notification_intervention = NotificationIntervention::where('id_notification', '=', $notification->id_notification)
                                           ->whereIn('id_UE', '=', $id_interventions)
                                           ->first();
                if(!empty($notification_intervention)){
                    $notification_en_attente = "warning";
                    $notification_exist = 1;
                }
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
				<button type="button" class="btn btn-default"  id="modal_ajout_autre">Autre (hors FST)</button>
			  </div>
			  <div class="alert alert-danger" role="alert" id="erreur_ajout_autre">
				<strong>Echec!</strong> Certaines informations ne sont pas valides, vérifier que vos données ne contiennent pas de nombres négatifs ou de caractères spéciaux.
			  </div>

			  <form class="form-vertical hidden" id="form_ajout_autre">
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

        <script type="text/javascript" src="/PPIL/assets/js/interventions.js"></script>
        <script type="text/javascript">
          $(function(){
		  ajouter("$lien_ajouter", "$lien_ajouter_autre");
          valider("$lien", $notification_exist, $depasse);
          exporter("$lien_exporter");
          remiseAZero("$lien_remiseAZero");
          });
        </script>


END;
        }
        $html = $html . self::footerHTML();
        return $html;
  }

    public function ue(){
        $scripts_and_css = "";
        $html  = self::headHTML($scripts_and_css);
        $html = $html . self::navHTML("UE");
        $html = $html . self::footerHTML();
        return $html;
    }


    public function enseignant(){
        $scripts_and_css = "";
        $html  = self::headHTML($scripts_and_css);
        $html = $html . self::navHTML("Enseignants");
        $html = $html . self::footerHTML();
        return $html;
    }

    public function journal(){
        $scripts_and_css = "";
        $html  = self::headHTML($scripts_and_css);
        $html = $html . self::navHTML("Journal");
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
				  <h4 class="navbar-text hidden-xs hidden-sm">
					Journal des modifications
				  </h4>
				  <h4 class="navbar-text hidden-md hidden-lg">
					Journal
				  </h4>
				</div>

				<div class="collapse navbar-collapse" id="navbar_panel">
				  <div class="navbar-right">
					<button type="button" class="btn btn-default navbar-btn" disabled="true" id="appliquer">Appliquer</button>
				  </div>
				</div>

			  </div>
			</div>
			<div class="panel-body text-center">
			<div class="table-responsive">
END;

        if(isset($_SESSION["mail"])){
            $e = Enseignant::where('mail', '=', $_SESSION["mail"])->first();
            $notifications = Notification::where('mail_destinataire', '=', $e->mail)
                           ->get();


            if (count($notifications) == 0) {
              $html .= "<label>Aucune notification</label>";
            } else {
              $html .= <<<END
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

            $html .= "</tbody></table>";
          }
        }

        $html .= <<<END
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
        $scripts_and_css = "";
        $html  = self::headHTML($scripts_and_css);
        $html = $html . self::navHTML("Annuaire");
        $lienAnnuaire = Slim::getInstance()->urlFor("annuaireUtilisateur");
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
      					Annuaire
      				  </h4>
      				</div>

				      <div class="collapse navbar-collapse" id="navbar_panel">
				        <div class="navbar-form navbar-right">
                  <div class="input-group">
                    <input type="text" class="form-control" id="rechercheEnseignant" placeholder="Recherche">
					             <div class="input-group-btn">
                        <button class="btn btn-default" id="boutonRecherche" >
                          <i class="glyphicon glyphicon-search"></i>
                        </button>
                        <button class="btn btn-default disabled" id="boutonAnnulerRecherche">
                          <i class="glyphicon glyphicon-remove" ></i>
                        </button>
					             </div>
                  </div>
				        </div>
				      </div>

			       </div>
			     </div>

            <div class="panel-body text-center">
			       <div class="table-responsive" id="tableEnseignants">
END;
        if (count($users) == 1) {
          $html .= "<label>Aucun enseignant</label>";
        } else {
          $html .= <<<END
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th class="text-center">Enseignant</th>
                          <th class="text-center">Statut</th>
                          <th class="text-center">Adresse Mail</th>
                          <th class="text-center">Photo</th>
                        </tr>
                      </thead><tbody>

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

          $html .= "</tbody></table>";
        }

        $html .= <<<END
			           </div>
            </div>
          </div>
        </div>

        <script type="text/javascript" src="/PPIL/assets/js/annuaire.js"></script>
         <script type="text/javascript">
           $(function(){
             rechercheEnseignants("$lienAnnuaire");
           });
         </script>

END;

        $html = $html . self::footerHTML();
        return $html;
    }

}
