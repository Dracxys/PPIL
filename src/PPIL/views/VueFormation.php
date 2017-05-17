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
        $html  = self::headHTML();
        $html .= self::navHTML("Formation");
        $select = self::selectStatut($u);
        $valider = Slim::getInstance()->urlFor('home');
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
                    <label id="nomUE" class="control-label">Sélectionner un UE</label>
                 </div>   
                 <div class="panel">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#tab1" data-toggle="tab">CM</a></li>
                        <li><a href="#tab2" data-toggle="tab">TD</a></li>
                        <li><a href="#tab3" data-toggle="tab">TP</a></li>
                        <li><a href="#tab4" data-toggle="tab">EI</a></li>
                   
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Volume attendu</th>
                                        <th class="text-center">Volume affecté</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAttenduCM"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAffecteCM"  value="0" readonly/> </th>
                                    </tr>
                                </thead>
                            </table>    
                        </div>
                        <div class="tab-pane" id="tab2">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nombre de groupes attendus</th>
                                        <th class="text-center">Nombre de groupes affectés</th>
                                        <th class="text-center">Volume attendu</th>
                                        <th class="text-center">Volume affecté</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAttenduTD"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAffecteTD"  value="0" readonly/> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAttenduTD"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAffecteTD"  value="0" readonly/> </th>
                                    </tr>
                                </thead>
                            </table> 
                        </div>
                        <div class="tab-pane" id="tab3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nombre de groupes attendus</th>
                                        <th class="text-center">Nombre de groupes affectés</th>
                                        <th class="text-center">Volume attendu</th>
                                        <th class="text-center">Volume affecté</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAttenduTP"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAffecteTP"  value="0" readonly/> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAttenduTP"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAffecteTP"  value="0" readonly/> </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane" id="tab4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nombre de groupes attendus</th>
                                        <th class="text-center">Nombre de groupes affectés</th>
                                        <th class="text-center">Volume attendu</th>
                                        <th class="text-center">Volume affecté</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAttenduEI"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAffecteEI"  value="0" readonly/> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAttenduEI"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAffecteEI"  value="0" readonly/> </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>                        
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