<?php


namespace PPIL\views;

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
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>

</head>
<body>
  <div class="jumbotron">
	<div class="container">
	  <div class="row">
		<div class="hidden-sm hidden-xs col-md-2">
          <div class="logo-univ">
            <img width="100" height="100" src="assets/images/logo-univ.png" />
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
    public static function navHTML() {
        $HTML= <<<END
        <nav class="navbar navbar-default">
          <div class="container-fluid">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="http://www.univ-lorraine.fr/">Univ-lorraine</a>
			</div>
			<div class="collapse navbar-collapse" id="navbar">
			  <ul class="nav navbar-nav">
				<li class="active"><a href="#">Profil</a></li>
				<li><a href="#">Enseignement</a></li>
				<li><a href="#">UE</a></li>
				<li><a href="#">Formation</a></li>
				<li><a href="#">Enseignants</a></li>
				<li><a href="#">Journal</a></li>
				<li><a href="#">Annuaire</a></li>
			  </ul>
			  <ul class="nav navbar-nav navbar-right">
				<p class="navbar-text hidden-xs">#Nom d'utilisateur#</p>
				<li><a href="#">Se déconnecter</a></li>
			  </ul>
			</div>
          </div>
        </nav>
END;
        return $HTML;
    }

    public static function footerHTML() {
        $HTML= <<<END
END;
        return $HTML;
    }
}
