<?php
/**
 * Created by PhpStorm.
 * User: LouisNavone
 * Date: 16/05/2017
 * Time: 11:17
 */

namespace PPIL\views;


use Slim\Slim;

class VueFormation extends AbstractView
{
    public function home($u){
        $html  = self::headHTML(4);
        $html .= self::navHTML("Formation");
        $select = self::selectStatut($u);
        $lienInfoForm = Slim::getInstance()->urlFor('infoForm');
        $html .= <<< END
        <div id="formation" class="panel-body panel-default container">
            <h2 class="panel-heading text-center">Formation</h2>
            <div class="container panel col-sm-6 ">
                <div id="selectForm" class="col-sm-10">
                    <label class="control-label col-sm-6" for="formation">Sélectionner Formation</label>
                    <div class="col-sm-6">
                        $select
                    </div>
                </div>
                <div id="tableUE" class="container panel col-sm-10">
                  
			    </div>
            </div>
            <div class="col-sm-6 ">
                <label id="ue" class="control-label col-sm-4">Sélectionner Formation</label>
            </div>
        </div>
        <script type="text/javascript" src="/PPIL/assets/js/formation.js">     </script>
        <script type="text/javascript">
           $(function(){
               recupererUE("$lienInfoForm");
               $('#selectForm').change(function() {
                    recupererUE("$lienInfoForm");
               });
			});
        </script>
END;
        return $html;

    }

    public static function selectStatut($for)
    {
        $html = '<select class="form-control" id="selectForm" name="selectForm">';
        $i = 0;
        foreach ($for as $value) {
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