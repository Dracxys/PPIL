<?php


namespace PPIL\views;
use PPIL\models\Enseignant;
use PPIL\models\Responsabilite;
use PPIL\models\Notification;
use Slim\App;
use Slim\Slim;

class AbstractView {
    public static function headHTML() {
        $HTML= <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PPIL">
    <meta name="author" content="">
    <title>PPIL </title>
    <link href="/PPIL/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="/PPIL/assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="jumbotron">
	<div class="container">
	  <div class="row">
		<div class="hidden-sm hidden-xs col-md-2">
          <div class="logo-univ">
            <img width="100" height="100" src="/PPIL/assets/images/logo-univ.png" />
          </div>
        </div>
        <div class="text-center col-md-8">
		  <h1>Service enseignant</h1>
        </div>
      </div>
	</div>
  </div>

END;
        return $HTML;
    }

    public static function navHTML($focus) {
        $responsable_enseignants = false;
        $responsable_formation = false;
        if(isset($_SESSION["mail"])){
            $e = Enseignant::where('mail', '=', $_SESSION["mail"])->first();
            $responsabilite = Responsabilite::where('id_resp', '=', $e->id_responsabilite)
                            ->first();
            $notifications_count = Notification::where('mail_destinataire', '=', $e->mail)
                             ->count();

            if(isset($responsabilite)){
                if($responsabilite->intituleResp == 'Responsable du departement informatique'){
                    $responsable_enseignants = true;
                    $responsable_formation = true;
                }
                if($responsabilite->intituleResp == 'Responsable formation'){
                    $responsable_formation = true;
                }
            }
        }
        $options = array(
            "Profil" => Slim::getInstance()->urlFor("profilUtilisateur"),
            "Enseignement" => Slim::getInstance()->urlFor("enseignementUtilisateur"),
            "UE" => Slim::getInstance()->urlFor("ueUtilisateur"),
            "Formation" => Slim::getInstance()->urlFor("formationUtilisateur"),
            "Enseignants" => Slim::getInstance()->urlFor("enseignantsUtilisateur"),
            "Journal" => Slim::getInstance()->urlFor("journalUtilisateur"),
            "Annuaire" => Slim::getInstance()->urlFor("annuaireUtilisateur")
        );
        $deco = Slim::getInstance()->urlFor("deconnexion");
        $HTML= <<< END
        <nav class="navbar navbar-default">
          <div class="container-fluid">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="http://www.univ-lorraine.fr/">Univ-lorraine</a>			</div>
			<div class="collapse navbar-collapse" id="navbar">
			  <ul class="nav navbar-nav">
END;
        foreach($options as $option => $link){
            $class = "";
            if($option == $focus){
                $class = 'class="active"';
            }
            switch($option){
            case 'Enseignants' :
                if($responsable_enseignants){
                    $HTML .= '<li ' . $class . '><a href="'. $link .'">'. $option .'</a></li>';
                }
                break;
            case 'Formation' :
                if($responsable_formation){
                    $HTML .= '<li ' . $class . '><a href="'. $link .'">'. $option .'</a></li>';
                }
                break;
            case 'Journal' :
                if($notifications_count > 0){
                    $HTML .= '<li ' . $class . '  ><a href="'. $link .'" id="notifications_count">'. $option . " <font color='red' id='notifications_count_font'>(" .  $notifications_count . ')</font></a></li>';
                }else{
                    $HTML .= '<li ' . $class . '><a href="'. $link .'">'. $option . '</a></li>';
                }
                break;
            default :
                $HTML .= '<li ' . $class . '><a href="'. $link .'">'. $option .'</a></li>';
                break;
            }
        }
        $HTML .= <<< END
			  </ul>
			  <ul class="nav navbar-nav navbar-right">
				<p class="navbar-text hidden-xs hidden-sm">
END;
        if(isset($_SESSION["mail"])){
            $e = Enseignant::where('mail', '=', $_SESSION["mail"])->first();
            $HTML .= $e->prenom ." ". $e->nom ."</p>";
        }
        $HTML .= <<< END
				<li><a href="$deco">Se déconnecter</a></li>
			  </ul>
			</div>
          </div>
        </nav>
END;
        return $HTML;
    }

    public static function footerHTML() {
        $HTML= <<< END
  </body>
</html>
END;
        return $HTML;
    }
}
