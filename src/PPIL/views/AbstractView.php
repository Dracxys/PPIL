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