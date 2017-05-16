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
            <div class="container panel col-sm-6 ">
                 <div class="container">
                    <label id="ue" class="control-label">Sélectionner Formation</label>
                 </div>   
                 <div class="panel">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#tab1" data-toggle="tab">CM</a></li>
                        <li><a href="#tab2" data-toggle="tab">TD</a></li>
                        <li><a href="#tab3" data-toggle="tab">TP</a></li>
                        <li><a href="#tab4" data-toggle="tab">EI</a></li>
                   
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">Vous pouvez consulter le détail du programme</div>
                        <div class="tab-pane" id="tab2">Vous pouvez consulter le détail sur le public</div>
                        <div class="tab-pane" id="tab3">Vous pouvez consulter le détail des objectifs pédagogiques</div>
                        <div class="tab-pane" id="tab4">Vous pouvez consulter le détail des compléments</div>
                    
                    </div>
                </div>    
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