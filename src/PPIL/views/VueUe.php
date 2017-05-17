<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 16/05/2017
 * Time: 19:18
 */


namespace PPIL\views;

use PPIL\models\UE;
use Slim\App;
use Slim\Slim;


class VueUe extends AbstractView
{

    public function home($u){
        $html  = self::headHTML();
        $html .= self::navHTML("UE");
        $select = self::selectUE($u);
        //$lienInfoUE = Slim::getInstance()->urlFor('infoUE');
        $html .= <<< END
        
        <div class="panel panel-default">
            <div class="panel-heading clearfix text-left">
                <h4 class="panel-heading text-center"></h4>
                
                <div id="selectUE" class="col-sm-10">
                    <label class="control-label col-sm-6" for="ue">Sélectionner UE</label>
                    <div class="col-sm-6">
                        $select
                    </div>
                </div>
            
                <div class="btn-group pull-right">
                    <form class="navbar-form navbar-left">
                        <button type="submit" class="btn btn-default">Importer</button>
                        <button type="submit" class="btn btn-default">Exporter</button>
                    </form>
                </div>
            
                
            </div>

            <div class="container text-center">
                 <div class="list-group">
                    <a href="#" id="boutonCompo" class="list-group-item active">Composition de l'UE</a>
                    <a href="#" id="boutonInterv" class="list-group-item">Liste des intervenants</a>
                </div>
            </div>

END;
        $html .= self::compositionUE($u);
        $html .= self::listeIntervenants($u);


        $html .= <<<END
        </div>
END;
        $html .= self::footerHTML();
        $html .= "      <script type=\"text/javascript\" src=\"/PPIL/assets/js/ue.js\">     </script>";

        return $html;

    }

    private function compositionUE($u) {
        $compoUE = Slim::getInstance()->urlFor("compoUE");
        $html = <<<END
            <div id="compoUE" style="display: none;">
                <form class="form-horizontal" method="post" action="$compoUE" enctype="multipart/form-data">
			        <h2 class="form-signin-heading ">Composition de l'UE</h2>
			        </form>
			        </div>

END;

        return $html;
    }

    private function listeIntervenants($u) {
        $intervenantsUE = Slim::getInstance()->urlFor("intervenantsUE");
        $html = <<<END
                <div id="intervenantsUE" style="display: none;">
                <form class="form-horizontal" method="post" action="$intervenantsUE" enctype="multipart/form-data">
			        <h2 class="form-signin-heading ">Intervenants de l'UE</h2>
			        </form>
			        </div>
END;

        return $html;
    }

    public static function selectUE($ue)
    {
        $html = '<select class="form-control" id="selectUE" name="selectUE">';
        $i = 0;
        foreach ($ue as $value) {
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