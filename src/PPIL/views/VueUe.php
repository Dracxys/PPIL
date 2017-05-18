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
        $lienInfoUE = Slim::getInstance()->urlFor('compoUE');
        $html .= <<< END
        
        <div class="panel panel-default">
            <div class="panel-heading clearfix text-left">
                <h4 class="panel-heading text-center"></h4>
                
                <div id="select" class="col-sm-10">
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
        // finir les fonctions avant de les décommenter

        $html .= self::compositionUE();
        $html .= self::listeIntervenants();


        $html .= <<<END
        </div>
END;
        $html .= self::footerHTML();
        $html .=<<<END
        <script type="text/javascript" src="/PPIL/assets/js/ue.js"></script>
        <script type="text/javascript">
           $(function(){
               setLien("$lienInfoUE")
               choixUE();
               listIntervenant();
               $('#selectUE').change(function() {
               choixUE();
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
                <button type="button" class="btn btn-default center-block" onclick="modifUE()" id="valider">Valider</button>
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
                                <th class="text-center">Statut</th>
                                <th class="text-center">Adresse Mail</th>
                                <th class="text-center">Photo</th>
                              </tr>
                              <tr id="tableau">
                              </tr>

END;

       /*foreach ($users as $user) {
            if ($user->prenom!="admin" && $user->nom!="admin" && $_SESSION['mail']!=$user->mail) {
                $html .= "<tr>" .
                        "<th class=\"text-center\">" . $user->prenom . " " . $user->nom . "</th>" .
                        "<th class=\"text-center\">" . $user->statut . "</th>" .
                        "<th class=\"text-center\">" . $user->mail . "</th>";

                if($user->photo == null){
                    $default = "/PPIL/assets/images/profil_pictures/default.jpg";
                    $html .= '<td class="center" ><img src="' . $default  .'" class="img-thumbnail" alt="Photo de profil" width="35" height="35"></td>';
                }else{
                    $html .= '<td class="center" ><img src=' . "/PPIL/" . $user->photo  .' class="img-thumbnail" alt="Photo de profil" width="35" height="35"></td>';
                }

                $html .= "</tr>";
            }
        }*/
        //ajout intervenant

        $html .= <<<END
            </thead>
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