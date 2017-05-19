<?php
/**
 * Created by PhpStorm.
 * User: thibautcrouvezier
 * Date: 16/05/2017
 * Time: 19:18
 */


namespace PPIL\views;

use PPIL\controlers\EnseignantsControler;
use PPIL\models\Enseignant;
use PPIL\models\UE;
use Slim\App;
use Slim\Slim;


class VueUe extends AbstractView
{

    public function home($u){
        $html  = self::headHTML();
        $html .= self::navHTML("UE");
        $select = self::selectUE($u);
        $lienInfoUE = Slim::getInstance()->urlFor('compoUE');
        $html .= <<< END
        <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading nav navbar-default">
            <div class="container-fluid">

				 <div class="navbar-header">
				  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar_panel">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <h4 class="navbar-text">
					UE
				  </h4>
				 </div>

				 <div class="collapse navbar-collapse" id="navbar_panel">
				   <div class=" navbar-right">
					 <div class="btn-group pull-right">
                       <form class="navbar-form navbar-left">
                         <button type="submit" class="btn btn-default">Importer</button>
                         <button type="submit" class="btn btn-default">Exporter</button>
                       </form>
					 </div>
				   </div>
				 </div>

			</div>
			</div>
			<div class="panel-body ">
			  <form class="form-horizontal">
				<div id="select" class="form-group">
                  <label class="control-label col-sm-3" for="selectUE">Sélectionner UE :</label>
				  <div class="col-sm-9">
					$select
					</div>
				</div>
			  </form>

              <div class="list-group list-group-horizontal text-center">
                <a href="#" id="boutonCompo" class="list-group-item active">Composition de l'UE</a>
                <a href="#" id="boutonInterv" class="list-group-item">Liste des intervenants</a>
                </div>
END;
        // finir les fonctions avant de les décommenter

        $html .= self::compositionUE();
        $html .= self::listeIntervenants();


        $html .= <<<END
					 </div>
		</div>
		</div>

END;
        $html .= self::footerHTML();
        $html .=<<<END
        <script type="text/javascript" src="/PPIL/assets/js/ue.js"></script>
        <script type="text/javascript">
           $(function(){
               setLien("$lienInfoUE")
               choixUE();
               boutonValidationModif();
               listIntervenant();
               $('#selectUE').change(function() {
               choixUE();
               boutonValidationModif();
               listIntervenant();
               });
               $('#erreur').hide();
			});
        </script>
END;
        return $html;

    }

    private function compositionUE() {
        $mes = self::message();
        $html = <<<END
            <div id="compoUE">
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
                <div class="panel-defaul container-fluid">
                <div id="boutton_validation">
                </div>
                <div id="erreur" class="alert alert-danger text-center">
                    <strong>Erreur : </strong> Chiffres négatifs dans un des champs.
                </div>
                $mes
            </div>
            </div>

            </div>
END;

        return $html;
    }

    private function listeIntervenants() {

        $html = <<<END
                <div id="intervenantsUE" style="display: none;">
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			        <h2 class="form-signin-heading ">Intervenants de l'UE</h2>
			        </form>

                    <div class="table-responsive">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th class="text-center">Enseignant</th>
                                <th class="text-center">Heures CM</th>
                                <th class="text-center">Nombres groupes TD</th>
                                <th class="text-center">Heures TD</th>
                                <th class="text-center">Nombres groupes TP</th>
                                <th class="text-center">Heures TP</th>
                                <th class="text-center">Nombres groupes EI</th>
                                <th class="text-center">Heures EI</th>
                              </tr>
                              </thead>
                              <tbody id="tableau">
                              </tbody>
                          </table>
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
                    $html .= '<option selected value=' . '"' . $value->id_UE . '"' . '>' . $value->nom_UE . '</option>';
                    $i ++;
                } else {
                    $html .= '<option value=' . '"' . $value->id_UE . '"' . '>' . $value->nom_UE . '</option>';
                }
            }

        }
        $html .= "</select>";
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

}
