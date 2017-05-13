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
</head>
<body>
  <div class="jumbotron">
	<div class="container">
	<div class="row">
      <div class="hidden-xs col-sm-2">
            <div class="logo-univ">
                <img width="100" height="100" src="assets/images/logo-univ.png" />
            </div>
        </div>
        <div class="col-sm-10">
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
END;
        return $HTML;
    }

    public static function footerHTML() {
        $HTML= <<<END
END;
        return $HTML;
    }
}
