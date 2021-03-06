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
        if(isset($_SESSION['mail'])){
            $e = Enseignant::where('mail', '=', $_SESSION["mail"])->first();
            $responsabilite = Enseignant::get_privilege($e);
            $class_responsable_autorise = "hidden disabled";
            $responForm = "hidden disabled";
            if(isset($responsabilite)){
                        $class_responsable_autorise = "";
            }
            if(isset($responsabilite) && $responsabilite > 0){
                $responForm = "";
            }
            $scripts_and_css = <<< END
			<link href="/PPIL/assets/css/list_horizontal.css" rel="stylesheet">
END;
            $html  = self::headHTML($scripts_and_css);
            $html .= self::navHTML("UE");
            $select = self::selectUE($u);
            $mes = self::message();
            $modifierUE = self::modifierUE();
            $lienInfoUE = Slim::getInstance()->urlFor('compoUE');
            $lien_exporter = Slim::getInstance()->urlFor('ue.exporter');
            $lien_importer = Slim::getInstance()->urlFor('ue.importer');
            $user = Enseignant::where("mail", "like", $_SESSION['mail'])->first();
            $e = Enseignant::where('mail', '=', $_SESSION["mail"])->first();
            $responsabilite = Enseignant::get_privilege($e);

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
					<button type="submit" class="btn btn-default navbar-btn $class_responsable_autorise" id="importer">Importer</button>
                    <button type="submit" class="btn btn-default navbar-btn " id="exporter">Exporter</button>
                    <button type='button' class='btn btn-primary $class_responsable_autorise' data-toggle="modal" data-target="#modal_ajoutEnseignant" id='ajoutEnseignant'>Ajouter enseignant</button>
                    <button type='button' class='btn btn-primary $responForm' id='modifierUE'>Modifier UE</button>
                    <form method="post" enctype="multipart/form-data" id="form_input_csv">
                        <input id="input_csv" name="file" type="file" class="hidden" />
                    </form>
				   </div>
				 </div>

			</div>
			</div>
			$mes
			$modifierUE
			<div class="alert alert-success hidden" role="alert" id="import_succes">
			  <strong>Succes!</strong> Votre fichier a bien été importé.
			</div>
			  <div class="alert alert-danger hidden" role="alert" id="import_erreur_extension">
				<strong>Echec!</strong> Votre fichier n'est pas de type csv.
			  </div>
			  <div class="alert alert-danger hidden" role="alert" id="import_erreur_taille">
				<strong>Echec!</strong> Votre fichier est trop volumineux.
			  </div>
			  <div class="alert alert-danger hidden" role="alert" id="import_erreur_autre">
				<strong>Echec!</strong> Une erreur non identifiée est survenue.
			  </div>
			  <div class="alert alert-danger hidden" role="alert" id="import_erreur_parse">
				<strong>Echec!</strong> Une erreur est survenue lors de traitement des données.
			  </div>

			  <div class="panel-body ">
				<div class="container">
				  <div class="col-md-6">
					<form class="form-horizontal">
					  <div id="select" class="form-group">
						<label class="control-label col-sm-5" for="selectUE">Sélectionner UE</label>
						<div class="col-sm-5">
						  $select
						</div>
					  </div>
					</form>
				  </div>
				  <div class="col-md-6">
					<div class="list-group list-group-horizontal text-center">
					  <a href="#" id="boutonCompo" class="list-group-item active">Composition de l'UE</a>
					  <a href="#" id="boutonInterv" class="list-group-item">Liste des intervenants</a>
					</div>
				  </div>
				</div>
END;
        // finir les fonctions avant de les décommenter

        $html .= self::compositionUE($class_responsable_autorise);
        $html .= self::listeIntervenants();
        $html .= self::ajoutEnseignant();

        $html .= <<< END
					 </div>
		</div>

END;
        $html .= self::footerHTML();
        $html .=<<< END
        <script type="text/javascript" src="/PPIL/assets/js/ue.js"></script>
        <script type="text/javascript">
           $(function(){
               exporter("$lien_exporter");
               importer("$lien_importer");
               setLien("$lienInfoUE");
		       setup();
		       $('#modalAnnuleModifUE').click(function() {
                    $('#modalModifierUE').modal('toggle');
               });
               $('#modifierUE').click(function() {
                    modifierUE();
               });
			});
        </script>
END;
return $html;
        }
    }

    private function compositionUE($class_responsable_autorise) {
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
                                        <th class="text-center"> <input type="number" class="form-control " id="heureAttenduCM"  value="0" min="0"/> </th>
                                        <th class="text-center"> <input type="number" class="form-control " id="heureAffecteCM"  value="0" min="0" readonly/> </th>
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

        $html = <<< END
                <div id="intervenantsUE" style="display: none;">
                <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
			        <h2 class="form-signin-heading text-center">Intervenants de l'UE</h2>
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
                                <th class="text-center">Actions</th>
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

    public function selectUE($ue)
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

    public function message(){
        $html = <<< END
        <div class="modal fade" id="modalDemandeEffectuee" role="dialog">
		    <div class="modal-dialog">
			    <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="messageTitre" class="modal-title">Succès</h4>
                    </div>
                    <div class="modal-body">
                        <p id="message">Les modifications ont bien été prises en compte.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
END;
        return $html;
    }

    public function ajoutEnseignant(){
        $html = <<<END
        <div class="container">
        <div class="modal fade" id="modal_ajoutEnseignant" role="dialog">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ajouter un enseignant</h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
            <table class="table table-bordered ">
                    <thead>
                      <tr>
                        <th class="text-center">Nom</th>
                        <th class="text-center">Prénom</th>
                        <th class="text-center">Mail</th>
						<th class="text-center">Sélectionner</th>
                      </tr>
                    </thead>
                    <tbody id="tableUEAjoutEnseignant">
                    </tbody>
                    </table>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="addEnseignants()" data-dismiss="modal">Appliquer</button>
                    </div>
                    </div>
            </div>
        </div>

      </div>
      </div>
    </div>
  </div>
END;
    return $html;
    }

    public function modifierUE()
    {
        $html = <<< END
        <div class="modal fade text-center" id="modalModifierUE" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h4 class="modal-title">Modifier UE</h4>
			  </div>
			  <div class="modal-body form-signin form-horizontal">
                <div class="form-group">
				    <label class="control-label col-sm-5" for="nomUE">Nom UE :</label>
				    <div class="col-sm-4">
				        <input type="text" id="nomUE" name="nomUE" class="form-control" placeholder="Nom UE" required="true"/>
				    </div>
			    </div>
			    <div class="form-group">
				        <label class="control-label col-sm-5" for="resp">Responsable : </label>
				        <div class="col-sm-4">
				            <select id="respForm1" class="form-control" name="respForm1">

				            </select>
				        </div>
			    </div>
			  </div>
			  <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="validerModifierUE()" id="modalValideMoifUE">Valider</button>
                <button type="button" class="btn btn-default"  id="modalAnnuleModifUE">Annuler</button>
			  </div>
			</div>
		  </div>
		</div>
END;
        return $html;
    }

}
