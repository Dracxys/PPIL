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
    public function home($u)
    {
        $scripts_and_css = "";
        $html  = self::headHTML($scripts_and_css);
        $html .= self::navHTML("Formation");
        $select = self::selectStatut($u);
        $form = self::creerForm();
        $valider = Slim::getInstance()->urlFor('home');
        $lienInfoForm = Slim::getInstance()->urlFor('formationUtilisateur');
        $mes = self::message();
        $ue = self::ajouterUE();
        $del = self::delete();
        $html .= <<< END
        <div class="container">
        <div id="formation" class="panel panel-default ">
            <div class="panel-heading nav navbar-default">
            <div class="container-fluid">

				 <div class="navbar-header">
				  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar_panel">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <h4 class="navbar-text">
					Formation
				  </h4>
				 </div>

				 <div class="collapse navbar-collapse" id="navbar_panel">
				   <div class=" navbar-right">
					 <div class="btn-group pull-right">
                       <form class="navbar-form navbar-left">
					  <button type="button" class="btn btn-primary "  id="creerForm">Créer une formation</button>
					  <button type="button" class="btn btn-default "  id="exporter">Exporter</button>
					  <button type="button" class="btn btn-default "  id="ajouterUE">Ajouter un UE</button>
					  <button type="button" class="btn btn-default "  id="modifierForm">Modifier la formation</button>
					  <button type="button" class="btn btn-danger "  id="suppForm">Supprimer la formation</button>
					 </form>
					 </div>
				   </div>
				 </div>

		  </div>
            </div>
            <div class="panel-body">
            <div class="form-horizontal container-fluid col-sm-6 ">
                <div id="selectFormDiv" class=" container-fluid col-sm-10">
                    <label class="control-label col-sm-6" for="formation">Sélectionner Formation</label>
                    <div class="container col-sm-6">
                        $select
                    </div>
                </div>
                <div id="tableUE" class="table-responsive container-fluid col-sm-10">

			    </div>
            </div>
            <div class=" panel-default container col-sm-6 ">
                 <div class="">
                    <label id="nomUE" class="control-label">Sélectionner une UE</label>
                 </div>
                 <div class="panel-default">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#tab1" data-toggle="tab">CM</a></li>
                        <li><a href="#tab2" data-toggle="tab">TD</a></li>
                        <li><a href="#tab3" data-toggle="tab">TP</a></li>
                        <li><a href="#tab4" data-toggle="tab">EI</a></li>

                    </ul>
                    <div class="tab-content table-responsive">
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
            <div class="panel-default container-fluid">

                <button type="button" class="btn  btn-primary pull-right" onclick="modifUE()" id="valider">Valider</button>
                <div id="erreur" class="alert alert-danger text-center">
                    <strong>Erreur : </strong> Chiffres négatifs dans un des champs.
                </div>
                $mes
                $form
                $ue
                $del
            </div>
            <div class=" panel-default">
                <div class="header">
                    <h2 id="nomFormation" class="text-center">Total du volume horaire </h2>
                </div>
                <div class="table-responsive container-fluid">
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
        </div>
        </div>
        <script type="text/javascript" src="/PPIL/assets/js/formation.js"></script>
        <script type="text/javascript">
           $(function(){
               recupererUE("$lienInfoForm");
               $('#selectForm').change(function() {
                    recupererUE("$lienInfoForm");
               });
               $('#erreur').hide();
               $('#modalAnnule').click(function() {
                    $('#modalAjouterForm').modal('toggle');
                    $('#modalValideModif').addClass('hidden');
                    $('#modalValide').removeClass('hidden');
               });  
               $('#modalValide').click(function(){
                    ajouterForm();
               });
               $('#ajouterUE').click(function(){
                    $('#modalAjouterUE').modal({
                         backdrop: 'static',
                         keyboard: false
                    });
                    enseignant();
               });
               $('#creerForm').click(function(){
                    creerForm();
               });
               $('#modalValideUE').click(function(){
                    ajouterUE();
               });
               $('#modalAnnuleUE').click(function() {
                    $('#modalAjouterUE').modal('toggle');
               });
               $('#suppForm').click(function() {
                    $('#deleteMess').text("Etes vous sûr de vouloir supprimer cette formation : " + $('#selectForm option:selected').val() + ".");
                    $('#delete').modal({
                         backdrop: 'static',
                         keyboard: false
                    });
               });
               $('#deleteAnnule').click(function() {
                    $('#delete').modal('toggle');
               });
               $('#deleteForm').click(function() {
                    supprimerForm();
               });
               $('#modifierForm').click(function(){
                    modifForm();
               });
               $('#modalValideModif').click(function(){
                    modifFormBase();
               });
			});
        </script>
END;
        $html .= self::footerHTML();
        return $html;

    }

    public function selectStatut($for)
    {
        $html = '<select class="form-control" id="selectForm" name="selectForm">';
        $i = 0;
        $val = array_pop($for);
        if ($val != 'DI') {
            $for[] = $val;
        }
        foreach ($for as $value) {
            if (isset($value)) {
                if ($i == 0) {
                    $html .= '<option selected value=' . '"' . $value . '"' . '>' . $value . '</option>';
                    $i++;
                } else {
                    $html .= '<option value=' . '"' . $value . '"' . '>' . $value . '</option>';
                }
            }

        }
        $html .= "</select>";
        if ($val == 'DI') {
            $html .= "<script type=\"text/javascript\">  $(function() { $('#creerForm').show(); $('#suppForm').show(); });</script>";
        } else {
            $html .= "<script type=\"text/javascript\">  $(function() { $('#creerForm').hide(); $('#suppForm').hide(); });</script>";
        }
        return $html;
    }

    public function message()
    {
        $html = <<< END
        <div class="modal fade" id="modalDemandeEffectuee" role="dialog">
		    <div class="modal-dialog ">
			    <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="messageTitre" class="modal-title">Succès</h4>
                    </div>
                    <div class="modal-body">
                        <p id="message">Les modifications ont bien été prises en compte.</p>
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

    public function creerForm()
    {
        $html = <<< END
        <div class="modal fade text-center" id="modalAjouterForm" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h4 class="modal-title">Ajouter une formation</h4>
			  </div>
			  <div class="modal-body form-signin form-horizontal">
                <div class="form-group">
				    <label class="control-label col-sm-5" for="nomForm">Nom de la formation :</label>
				    <div class="col-sm-4">
				        <input type="text" id="nomForm" name="nomForm" class="form-control" placeholder="Nom de la formation" required="true"/>
				    </div>
			    </div>
			    <div class="form-group">
				        <label class="control-label col-sm-5" for="resp">Responsable 1 : </label>
				        <div class="col-sm-4">
				            <select id="respForm1" class="form-control" name="respForm1">
				             
				            </select>
				        </div>
			    </div>
			    <div class="form-group">
				        <label class="control-label col-sm-5" for="resp">Responsable 2 : </label>
				        <div class="col-sm-4">
				            <select id="respForm2" class="form-control" name="respForm2">
				             
				            </select>
				        </div>
			    </div>
			    <div class="form-group">
				        <label class="control-label col-sm-5" for="resp">Responsable  3: </label>
				        <div class="col-sm-4">
				            <select id="respForm3" class="form-control" name="respForm3">
				             
				            </select>
				        </div>
			    </div>
			    <div class="form-group">
				        <label class="control-label col-sm-5" for="resp">Responsable 4 : </label>
				        <div class="col-sm-4">
				            <select id="respForm4" class="form-control" name="respForm4">
				             
				            </select>
				        </div>
			    </div>
			  </div>
			  <div class="modal-footer">
                <button type="button" class="btn btn-primary hidden"  id="modalValideModif">Valider</button>
                <button type="button" class="btn btn-primary"  id="modalValide">Valider</button>
                <button type="button" class="btn btn-default"  id="modalAnnule">Annuler</button>
			  </div>
			</div>
		  </div>
		</div>
END;
        return $html;
    }

    public function ajouterUE()
    {
        $html = <<< END
        <div class="modal fade text-center" id="modalAjouterUE" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h4 class="modal-title">Ajouter un UE</h4>
			  </div>
			  <div class="modal-body">
                <form class="form-signin form-horizontal" method="post" action="" id="ajoutUE">
                    <div class="form-group">
				        <label class="control-label col-sm-5" for="nomUEForm">Nom UE :</label>
				        <div class="col-sm-4">
				            <input type="text" id="nomUEForm" name="nomUEForm" class="form-control" placeholder="Nom UE" required="true"/>
				        </div>
			        </div>
                    <div class="form-group">
				        <label class="control-label col-sm-5" for="heureCMForm">Heure CM :</label>
				        <div class="col-sm-4">
				            <input type="number" id="heureCMForm" name="heureCMForm" class="form-control" min="0" value="0" placeholder="0" />
				        </div>
			        </div>
			        <div class="form-group">
				        <label class="control-label col-sm-5" for="nbGroupeTDForm">Nombre de Groupe TD :</label>
				        <div class="col-sm-4">
				            <input type="number" id="nbGroupeTDForm" name="nbGroupeTDForm" class="form-control" min="0" value="0" placeholder="0" />
				        </div>
			        </div>
			        <div class="form-group">
				        <label class="control-label col-sm-5" for="heureTDForm">Heure TD :</label>
				        <div class="col-sm-4">
				            <input type="number" id="heureTDForm" name="heureTDForm" class="form-control" min="0" value="0" placeholder="0" />
				        </div>
			        </div>
			        <div class="form-group">
				        <label class="control-label col-sm-5" for="nbGroupeTPForm">Nombre de Groupe TP :</label>
				        <div class="col-sm-4">
				            <input type="number" id="nbGroupeTPForm" name="nbGroupeTPForm" class="form-control" min="0" value="0" placeholder="0" />
				        </div>
			        </div>
			        <div class="form-group">
				        <label class="control-label col-sm-5" for="heureTPForm">Heure TP :</label>
				        <div class="col-sm-4">
				            <input type="number" id="heureTPForm" name="heureTPForm" class="form-control" min="0" value="0" placeholder="0" />
				        </div>
			        </div>
			        <div class="form-group">
				        <label class="control-label col-sm-5" for="nbGroupeEIForm">Nombre de Groupe EI :</label>
				        <div class="col-sm-4">
				            <input type="number" id="nbGroupeEIForm" name="nbGroupeEIForm" class="form-control" min="0" value="0" placeholder="0" />
				        </div>
			        </div>
			        <div class="form-group">
				        <label class="control-label col-sm-5" for="heureEIForm">Heure EI :</label>
				        <div class="col-sm-4">
				            <input type="number" id="heureEIForm" name="heureEIForm" class="form-control" min="0" value="0" placeholder="0" />
				        </div>
			        </div>
                    <div class="form-group">
				        <label class="control-label col-sm-5" for="resp">Responsable : </label>
				        <div class="col-sm-4">
				            <select id="resp" class="form-control" name="resp">

				            </select>
				        </div>
			        </div>
			    </form>
              </div>
			  <div class="modal-footer">
                <button type="button" class="btn btn-primary"  id="modalValideUE">Valider</button>
                <button type="button" class="btn btn-default"  id="modalAnnuleUE">Annuler</button>
			  </div>
			  </div>
		    </div>
		  </div>
        </div>
END;
        return $html;
    }

    public function delete(){
        $html = <<< END
        <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirmer la suppression</h4>
                </div>

                <div class="modal-body">
                    <p id="deleteMess">Etes vous sûr de vouloir supprimer cette formation</p>
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" id="deleteAnnule" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    <a id="deleteForm" class="btn btn-danger btn-ok">Supprimer</a>
                </div>
            </div>
        </div>
    </div>
</div>
END;
        return $html;

    }

}