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
        //$volFST = self::getVolumeFST($u);
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
            if ($user->prenom!="admin" && $user->nom!="admin" && $_SESSION['mail']!=$user->mail) {
                if($user->volumeCourant==NULL) {
                    $volumeCourant=0;
                } else {
                    $volumeCourant=$user->volumeCourant;
                }
                $html .= "<tr>" .
                    "<th class=\"text-center\">" . $user->prenom . " " . $user->nom . "</th>" .
                    "<th class=\"text-center\">" . $user->statut . "</th>" .
                    "<th class=\"text-center\">" . $user->volumeMin . "</th>" .
                    "<th class=\"text-center\">" . $volumeCourant . "</th>" .
                    //"<th class=\"text-center\">" . $volFST . "</th>" .
                    "<th class=\"text-center\">" . $volumeCourant . "</th>" .
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



    public static function getVolumeFST($u)
    {
        $volume = 0;

        $interventions = Intervention::where('mail_enseignant','=', $u->mail)->get();
        foreach($interventions as $intervention) {
            if($intervention->fst == true) {

                $volume += $intervention->heuresCM + $intervention->heuresTD + $intervention->heuresTP + $intervention->heuresEI;

            }
        }

        return $volume;
    }
}