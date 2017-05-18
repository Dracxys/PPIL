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
        $form = self::creerForm();
        $valider = Slim::getInstance()->urlFor('home');
        $lienInfoForm = Slim::getInstance()->urlFor('infoForm');
        $mes = self::message();
        $html .= <<< END
        <div id="formation" class="panel-body panel-default ">
            <h2 class="panel-heading text-center">Formation</h2>
            <div class=" panel-default col-sm-6 ">
                <div id="selectForm" class="col-sm-10">
                    <label class="control-label col-sm-6" for="formation">Sélectionner Formation</label>
                    <div class="col-sm-6">
                        $select
                    </div>
                </div>
                <div id="tableUE" class=" panel-default col-sm-10">
                  
			    </div>
            </div>
            <div class=" panel-default col-sm-6 ">
                 <div class="">
                    <label id="nomUE" class="control-label">Sélectionner un UE</label>
                 </div>   
                 <div class="panel-default">
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
                                </thead>
                                <tbody>    
                                    <tr>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAttenduCM"  value="0" min="0"/> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAffecteCM"  value="0" min="0" readonly/> </th>
                                    </tr>
                                </tbody>
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
                                </thead>    
                                <tbody>
                                    <tr>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAttenduTD" min="0"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAffecteTD" min="0"  value="0" readonly/> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAttenduTD"  min="0" value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAffecteTD" min="0"  value="0" readonly/> </th>
                                    </tr>
                                </tbody>
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
                                </thead>    
                                <tbody>
                                    <tr>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAttenduTP" min="0" value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAffecteTP" min="0"  value="0" readonly/> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAttenduTP" min="0" value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAffecteTP" min="0"  value="0" readonly/> </th>
                                    </tr>
                                </tbody>
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
                                </thead>    
                                <tbody>
                                    <tr>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAttenduEI" min="0"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="nbGroupeAffecteEI" min="0"  value="0" readonly/> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAttenduEI" min="0"  value="0" /> </th>
                                        <th class="text-center"> <input type="number" class="form-control" id="heureAffecteEI" min="0"  value="0" readonly/> </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                </div>    
            </div>
            <div class="panel-defaul container-fluid">
                <button type="button" class="btn btn-default pull-left" onclick="creerForm()" id="creerForm">Creer une formation</button>
                <button type="button" class="btn  btn-primary pull-right" onclick="modifUE()" id="valider">Valider</button>
                <div id="erreur" class="alert alert-danger text-center">
                    <strong>Erreur : </strong> Chiffres négatifs dans un des champs.
                </div>
                $mes
                $form
            </div>
            <div class=" panel-default">
                <div class="header">
                    <h2 id="nomFormation" class="text-center">Total du volume horaire </h2>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered ">
                    <thead>
                      <tr>
                        <th class="text-center"></th>
                        <th class="text-center">Volume attendu CM</th>
                        <th class="text-center">Volume affecté CM</th>
                        <th class="text-center">Volume attendu TD</th>
                        <th class="text-center">Volume affecté TD</th>
                        <th class="text-center">Volume attendu TP</th>
                        <th class="text-center">Volume affecté TP</th>
                        <th class="text-center">Volume attendu EI</th>
                        <th class="text-center">Volume affecté EI</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="text-center">Total</th>
                            <th id="volumeAttenduCM" class="text-center">0</th>
                            <th id="volumeAffecteCM" class="text-center">0</th>
                            <th id="volumeAttenduTD" class="text-center">0</th>
                            <th id="volumeAffecteTD" class="text-center">0</th>
                            <th id="volumeAttenduTP" class="text-center">0</th>
                            <th id="volumeAffecteTP" class="text-center">0</th>
                            <th id="volumeAttenduEI" class="text-center">0</th>
                            <th id="volumeAffecteEI" class="text-center">0</th>
                        </tr>
                    </tbody>
                </table>  
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
               $('#erreur').hide();
               $('#modalAnnule').click(function() {
                    $('#modalAjouter').modal('toggle');
               });  
               $('#modalValide').click(function(){
                    ajouterForm();
               });
			});
        </script>
END;
        $html .= self::footerHTML();
        return $html;

    }

    public static function selectStatut($for)
    {
        $html = '<select class="form-control" id="selectForm" name="selectForm">';
        $i = 0;
        $val = array_pop($for);
        if($val != 'DI'){
           $for[] = $val;
        }
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
        if($val == 'DI'){
            $html .= "<script type=\"text/javascript\">  $(function() { $('#creerForm').show(); });</script>";
        }else{
            $html .= "<script type=\"text/javascript\">  $(function() { $('#creerForm').hide(); });</script>";
        }
        return $html;
    }

    public static function message(){
        $html = <<< END
        <div class="modal fade" id="modalDemandeEffectuee" role="dialog">
		    <div class="modal-dialog">
			    <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="messageTitre" class="modal-title">Succès</h4>
                    </div>
                    <div class="modal-body">
                        <p id="message">Les modifications ont bien été pris en compte.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
END;
        return $html;
    }

    public static function creerForm(){
        $html = <<< END
        <div class="modal fade text-center" id="modalAjouter" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h4 class="modal-title">Ajouter une formation</h4>
			  </div>
			  <div class="modal-body form-signin form-horizontal">
                <div class="form-group">
				    <label class="control-label col-sm-4" for="nomForm">Nom de la formation :</label>
				    <div class="col-sm-6">
				        <input type="text" id="nomForm" name="nomForm" class="form-control" placeholder="Nom de la formation" required="true"/>
				    </div>
			    </div>
			    <div class="form-group">
				    <label class="control-label col-sm-4" for="fst">Formation de la FST : </label>
				    <div class="col-sm-6">
				        <select class="form-control" id="selectFst">
				            <option selected value="1">Oui</option>
				            <option value="0">Non</option>
				        </select>
				    </div>
			    </div>
			  </div>
			  <div class="modal-footer">
                <button type="button" class="btn btn-primary"  id="modalValide">Valider</button>
                 <button type="button" class="btn btn-default"  id="modalAnnule">Annuler</button>
			  </div>
			</div>
		  </div>
		</div>
END;
        return $html;
    }
}