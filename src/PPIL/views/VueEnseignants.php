<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 17/05/2017
 * Time: 15:33
 */

namespace PPIL\views;


use Slim\App;
use Slim\Slim;
use PPIL\models\Enseignant;
use PPIL\models\Formation;


class VueEnseignants extends AbstractView{

    public function home($u){
        $html  = self::headHTML();
        $html .= self::navHTML("Enseignants");
        $select = self::selectEnseignants($u);
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
                        $html .= "<tr>" .
                                "<th class=\"text-center\">" . $user->prenom . " " . $user->nom . "</th>" .
                                "<th class=\"text-center\">" . $user->statut . "</th>" .
                                "<th class=\"text-center\">" . $user->volumeMin . "</th>" .
                                "<th class=\"text-center\">" . $user->volumeCourant . "</th>" .
                                "<th class=\"text-center\">" . $user->volumeCourant . "</th>" .
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

    public static function selectEnseignants($u)
    {
        $html = '<select class="form-control" id="selectEnseignants" name="selectEnseignants">';
        $i = 0;
        foreach ($u as $value) {
            if(isset($value)){
                if ($i == 0) {
                    $html .= '<option selected value=' . '"' . $value . '"' . '>' . $value . '</option>';
                    $i ++;
                } else {
                    $html .= '<option value=' . '"' . $value . '"' . '>' . $value . '</option>';
                }
            }

        }
        $html .= "</select>";
        return $html;
    }
}


