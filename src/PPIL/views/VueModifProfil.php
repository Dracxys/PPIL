<?php

namespace PPIL\views;


use PPIL\models\Enseignant;
use PPIL\models\Formation;
use PPIL\models\Intervention;
use PPIL\models\UE;
use Slim\App;
use Slim\Slim;

class VueModifProfil extends AbstractView
{
    public function home($user, $num)
    {
        $modifresp = Slim::getInstance()->urlFor("modificationResponsabilite");
        $scripts_and_css = <<< END
            <script type="text/javascript" src="/PPIL/assets/js/jquery.circliful.min.js"></script>
            <script type="text/javascript" src="/PPIL/assets/js/modifprofil.js"></script>
			<link href="/PPIL/assets/css/jquery.circliful.css" rel="stylesheet" type="text/css" />
			<link href="/PPIL/assets/css/list_horizontal.css" rel="stylesheet">
END;
		$lienDesinscription = Slim::getInstance()->urlFor('desinscription');
        $html  = self::headHTML($scripts_and_css);
        $html = $html . self::navHTML("Profil");
        $html = $html . <<< END
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
					Profil
				  </h4>
				 </div>
				 <div class="collapse navbar-collapse " id="navbar_panel">
				   <form class="navbar-form navbar-right" >
				   	 <button  id="boutonDesinscription" class='btn btn-danger' onclick=location.href='$lienDesinscription' >Se désinscrire</button>
				   </form>

				   <div class="nav navbar-nav navbar-right">
					   <div class="list-group list-group-horizontal " id="liste_groupe">
						 <a  id="boutonInfo" class="list-group-item active">Informations personnelles</a>
						 <a  id="boutonResp" class="list-group-item">Responsabilités</a>
						 <a  id="boutonPhoto" class="list-group-item">Photo</a>


						 <a  id="boutonPassword" class="list-group-item">Mot de passe</a>

END;
        if(Enseignant::get_privilege($user)==2){
						 $html.='<a  id="boutonReinitialiser" class="list-group-item">Réinitialiser BDD</a>';
        }
                       $html.=<<<END
						</div>
				   </div>
				 </div>
				</div>

		  </div>

        <div class="panel-body">
END;
        if ($num == 0) {
            $html .= <<< END
            <div class="alert alert-success" role="alert">
                <strong>Succès!</strong> Modification de profil validé.
            </div>
END;
        }
        if ($num == 1) {
            $html .= <<< END
            <div class="alert alert-success" role="alert">
                <strong>Succès!</strong> Modification du mot de passe validé.
            </div>
END;
        }
        if ($num == 2) {
            $html .= <<< END
            <div class="alert alert-danger" role="alert">
                <strong>Echec!</strong> Ancien mot de passe non valide.
            </div>
END;
        }
        if ($num == 3) {
            $html .= <<< END
            <div class="alert alert-danger" role="alert">
                <strong>Echec!</strong> Nouveau mot de passe et la confirmation sont différents.
            </div>
END;
        }
        if ($num == 4) {
            $html .= <<< END
            <div class="alert alert-danger" role="alert">
                <strong>Echec!</strong> Erreur de transfert.
            </div>
END;
        }
        if ($num == 5) {
            $html .= <<< END
            <div class="alert alert-danger" role="alert">
                <strong>Echec!</strong> Le fichier est trop gros.
            </div>
END;
        }
        if ($num == 6) {
            $html .= <<< END
            <div class="alert alert-danger" role="alert">
                <strong>Echec!</strong> Extension du fichier non valide.
            </div>
END;
        }
        if ($num == 7) {
            $html .= <<< END
            <div class="alert alert-success" role="alert">
                <strong>Succès</strong> Photo de profil changée.
            </div>
END;
        }
      if ($num == 8) {
          $html .= <<< END
            <div class="alert alert-success" role="alert">
                <strong>Succès</strong> Demande de responsabilité effectuée.
            </div>
END;
      }
       $html .= <<< END
                <div id="infoperso" class="container-fluid">
                    <div class="row">

END;

        $html .= self::infoperso($user);
        $html .= self::horaireEffect($user);
        $html .= <<< END
                    </div>
                </div>
END;

        $html .= self::responsabilite($user);
        $html .= self::photo($user);
        $html .= self::password($user);
        $html .= self::reinitialiser($user);

        $html .= <<< END
           </div>
           </div>
           </div>
           </div>
END;
        $html .= self::footerHTML();
        $html .= <<< END

		  <script type="text/javascript">
          $(function(){
			  setup("$modifresp");
		  });
	  </script>

END;
        return $html;
    }

    public function infoperso($user)
    {
        $modifprofil = Slim::getInstance()->urlFor("modificationProfil");
        $select = self::selectStatut($user);
        $html = <<< END
        <div class="col-md-7 text-center">
			<form class="form-signin form-horizontal" method="post" action="$modifprofil"  id="valider">
			  <h2 class="form-signin-heading">Modification du profil</h2>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="nom">Nom </label>
				<div class="col-sm-4">
				  <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" required="true" value="$user->nom" />
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="prenom">Prénom </label>
				<div class="col-sm-4">
				  <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Prénom" required="true" value="$user->prenom" />
				</div>
			  </div>
              <div class="form-group">
				<label class="control-label col-sm-4" for="email">Adresse Mail </label>
				<div class="col-sm-4">
				  <input type="email" id="email" name="email" class="form-control" placeholder="Adresse Mail" required="true" value=$user->mail />
				</div>
			  </div>
			  <div class="form-group">
				<label class="control-label col-sm-4" for="statut">Statut </label>
				<div class="col-sm-4">
END;
        $html .= $select;
        $html .= <<< END
				</div>
			  </div>
			  <div class="form-group">
				<button type="submit" class="btn btn-primary">Valider</button>
              </div>
			</form>
            </div>

END;
        return $html;
    }

    public function responsabilite($user)
    {
        $modifresp = Slim::getInstance()->urlFor("modificationResponsabilite");
        $u = UE::where('fst','=',1)->get();
        $f = Formation::where('fst','=',1)->get();

        $html = <<<END
                 <div class="container">
				   <div id="responsabilite" style="display: none;" class="">
                     <h2 class="text-center">Modification des responsabilités</h2>

                     <div class="">
                     <div class='col-md-5'>
                     </div>
                     <div class='col-md-4'>
                       <form class="form-horizontal" id="form_resp" method="post" action="$modifresp">
						 <div class="form-group">
						   <div class="radio">
							 <label class="radio-inline"><input type="radio" name="responsabilite" id="respUE" value="responsableUE">  Responsable d'UE </label>
                           </div>
                            <div class='col-md-2'>
                            </div>
                            <div class='col-md-5'>
                           <div id="UE" class="form-group" style="display:none;">
END;
        foreach($u as $value){
            $html.= <<< END
                    <div class="radio">
                    <input type="radio" name="ueSelect" value="$value->nom_UE">$value->nom_UE
                    </div>
END;
        }
        $html .= <<< END
					 </div>
					 </div>
                     <div class='col-md-5'>

                     </div>
					 </div>
					 <div class="form-group">
                       <div class="radio">
                         <label class="radio-inline"><input type="radio" name="responsabilite" id="respForm" value="responsableForm">Responsable de formation</label>
                       </div>
                       <div class='col-md-2'>
                       </div>
                       <div class='col-md-5'>
                       <div id="formation" class="form-group" style="display: none">
END;
        foreach($f as $value){
            $html.= <<< END
                    <div class="radio">
                    <input type="radio" name="formSelect" value="$value->nomFormation">$value->nomFormation
                    </div>
END;
        }

        $html .= <<< END
                     </div>
                     </div>
                     <div class='col-md-5'>
                     </div>
					 </div>
					 <div class="form-group">
                                <input type="submit" class="btn btn-primary" id="submit_resp">
                    </div>
                    </form>
                    </div>
                     <div class='col-md-3'>
                     </div>
        </div>
              </div>

END;
        return $html;
    }


    public function photo($user)
    {
        $modifphoto = Slim::getInstance()->urlFor("modificationPhoto");
        $html = <<< END
                <div id="photo" style="display: none;" class="text-center">
                    <form class="form-horizontal" method="post" action="$modifphoto" enctype="multipart/form-data">
			        <h2>Modification de la photo du profil</h2>
                    <div class="form-group">
                    	<div class="col-2">
END;
                    if($user->photo == null){
                        $default = "/PPIL/assets/images/profil_pictures/default.jpg";
                        $html .= '<img src=' . $default  .' class="img-thumbnail" alt="Photo de profil" width="304" height="236">';
                    }else{
                        $html .= '<img src=' . "/PPIL/" . $user->photo  .' class="img-thumbnail" alt="Photo de profil" width="304" height="236">';
                    }
                        $html .= <<< END
                        </div>
                    </div>

                    <div class="form-group">
                    	<div class="btn btn-file">
                        <input type="file" name="file" />
                        </div>
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn btn-primary" />
                    </div>
                    </form>
                    </div>
END;

        return $html;
    }

    public function password($user)
    {
        $modifpassword = Slim::getInstance()->urlFor("modificationPassword");
        $html = <<< END
			<div class="container">
			  <div id="motdepasse" style="display: none;" class="text-center">
        		<h2>Modification du mot de passe</h2>

				<div class="text-center">
				  <form class="form-horizontal" method="post" action="$modifpassword"  id="valider">
					<div class="form-group">
					  <label class="control-label col-sm-5" for="ancien">Ancien mot de passe</label>
					  <div class="col-sm-4">
						<input type="password" id="ancien" name="ancien" class="form-control" placeholder="Ancien mot de passe" required="true"/>
					  </div>
					</div>
					<div class="form-group">
					  <label class="control-label col-sm-5" for="nouv">Nouveau mot de passe</label>
					  <div class="col-sm-4">
						<input type="password" id="nouv" name="nouv" class="form-control" placeholder="Nouveau mot de passe" required="true" />
					  </div>
					</div>
					<div class="form-group">
					  <label class="control-label col-sm-5" for="conf">Confirmation du nouveau mot de passe</label>
					  <div class="col-sm-4">
						<input type="password" id="conf" name="conf" class="form-control" placeholder="Confirmer nouveau mot de passe" required="true"/>
					  </div>
					</div>
					<div class="form-group">
					  <button type="submit" class="btn btn-primary">Valider</button>
					</div>
				  </form>
				</div>
			  </div>
			</div>
END;
        return $html;
    }

    public function reinitialiser($user){
		$lienReinitialisation = Slim::getInstance()->urlFor('reinitialiser');
        $html=<<< END
                <div class="container">
                    <div id="reinitialiser" style="display: none;" class="text-center">
                    <label>L'appuie sur ce bouton entrainera la suppression de la base de données<br/>(UE, Enseignants, Formations)</label>
                    <br/>
                    <a  id="boutonValiderReinitialisation" class='btn btn-danger' onclick=location.href='$lienReinitialisation' >Reinitialisation</a>
   </div>
                </div>

END;

                return $html;
    }

    public function selectStatut($user)
    {
        $array = array('Professeur des universités', 'Maître de conférences', 'PRAG', 'ATER', '1/2 ATER', 'Doctorant', 'Vacataire');
        $html = '<select class="form-control" name="statut">';
        foreach ($array as $value) {
            if ($value == $user->statut) {
                $html .= '<option selected value=' . '"' . $value . '"' . '>' . $value . '</option>';
            } else {
                $html .= '<option value=' . '"' . $value . '"' . '>' . $value . '</option>';
            }
        }
        $html .= "</select>";
        return $html;
    }

    public function horaireEffect($user){
        $volumeCourant = $user->volumeCourant;
        if(is_null($volumeCourant)) $volumeCourant = 0;
        $volumeMin = $user->volumeMin;
        $volumeMax = $user->volumeMax;
        $pourcentage = Enseignant::getPourcentageVolumeHoraire($user);
        $html = <<< END
                    <div class="col-md-4 text-center">
                       <h2 class="form-signin-heading ">Charge horaire minimum</h2>
                        <div class="text-center" id="cercle" title="Charge horaire minimum" data-animation="1" data-animationStep="5" data-percent="$pourcentage"></div>
				        <label class="control-label ">Volume horaire courant : $volumeCourant</label><br>
			            <label class="control-label ">Volume horaire minimum : $volumeMin</label><br>
				        <label class="control-label ">Volume horaire maximum : $volumeMax</label><br>

                    </div>
END;
        return $html;
    }
}
