<?php


namespace PPIL\views;

use Slim\Slim;

class AbstractView {
    public static function headHTML() {
        $app = Slim::getInstance();
        //$base = $app->request->getRootUri();
        $HTML= <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>PPIL </title>
    <link href="../../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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