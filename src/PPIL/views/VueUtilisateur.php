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
        return $html;
    }

    public function journal(){
        $html = self::headHTML();
        $html = $html . self::navHTML("Journal");
        $html .= <<< END
	    <div class="container">
		  <div class="panel panel-default text-center">
			<div class="panel-heading">Journal des modifications</div>
			<div class="panel-body">
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
            $notifications = Notification::where('mail_destinataire', '=', $e->mail)->get();
            foreach($notifications as $notification){
                $date = date('d/m/Y', strtotime($notification->created_at));
                $description = array($notification->message);
                $nom_source = "";
                $prenom_source = "";
                $lien = Slim::getInstance()->urlFor("JournalUtilisateur.actionNotification");

                switch($notification->type_notification){
                case "PPIL\models\NotificationInscription":
                    $notificationinscription = Notificationinscription::where('id_notification', '=', $notification->id_notification)->get()->first();
                    $nom_source = $notificationinscription->nom;
                    $prenom_source = $notificationinscription->prenom;
                    break;
                default:
                    $enseignant_source = Enseignant::where('mail', '=',$notification->mail_source)->first();
                    $nom_source = $enseignant_source->nom;
                    $prenom_source = $enseignant_source->prenom;
                    break;
                }
                $html .= "<tr><td>" . $nom_source . " " . $prenom_source . "</td><td>";
                foreach($description as $item){
                    $html .= "<p>" . $item ."</p>";
                }
                $html .= "</td><td>" . $date ."</td>";
                $html .= <<< END
				 <td>
				   <form class="form-inline" method="post" action="$lien" >
					 <div class="form-group">
					   <input type="hidden" name="id" value="$notification->id_notification" />
					   <button  name="valider" class="btn btn-default" value="false" type="submit">Refuser</button>
					   <button  name="valider" class="btn btn-primary" value="true" type="submit">Accepter</button>
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
END;
        return $html;
    }
}
