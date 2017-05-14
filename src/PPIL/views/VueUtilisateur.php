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
use PPIL\models\Notification;
use PPIL\models\NotificationInscription;


class VueUtilisateur extends AbstractView
{
    public static function home(){
        $html = self::headHTML();
        $html = $html . self::navHTML("Profil");
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
						  <p>Votre demande a été prise en compte.</p>
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
        <button type="button" class="btn" id="bbb">Basic</button>
        <script type="text/javascript" src="/PPIL/assets/js/cleanup.js"></script>

END;

        $html = $html . self::footerHTML();

        return $html;
    }
}
