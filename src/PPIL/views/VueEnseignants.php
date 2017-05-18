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

class VueEnseignants extends AbstractView{
    public function home($u){
        $html  = self::headHTML();
        $html .= self::navHTML("Enseignants");
        $html .= <<< END
      
        <div class="container">
		  <div class="panel panel-default">
			<div class="panel-heading clearfix text-center">
			  <div class="btn-group pull-right">
				<button type="button" class="btn btn-default" id="exporterEnseignants">Exporter</button>
			  </div>
			  <h4>Enseignants</h4>
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

        foreach ($u as $user) {
            if ($_SESSION['mail']!=$user->mail) {
                if($user->volumeCourant==NULL) {
                    $volumeCourant=0;
                } else {
                    $volumeCourant=$user->volumeCourant;
                }
                $volFST = self::getVolumeFST($user);
                $html .= "<tr>" .
                    "<th class=\"text-center\">" . $user->prenom . " " . $user->nom . "</th>" .
                    "<th class=\"text-center\">" . $user->statut . "</th>" .
                    "<th class=\"text-center\">" . $user->volumeMin . "</th>" .
                    "<th class=\"text-center\">" . $volumeCourant . "</th>" .
                    "<th class=\"text-center\">" . $volFST . "</th>" .

                    "</tr>";
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


END;
        $html .= self::footerHTML();
        //$html .= "      <script type=\"text/javascript\" src=\"/PPIL/assets/js/enseignants.js\">     </script>";
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