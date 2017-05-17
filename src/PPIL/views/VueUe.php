<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 16/05/2017
 * Time: 19:18
 */


namespace PPIL\views;


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
                <h4 class="panel-heading text-center">UE</h4>
                
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