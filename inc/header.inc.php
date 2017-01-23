<?php

array_walk_recursive($_GET, function (&$val) { $val = trim($val); });
array_walk_recursive($_POST, function (&$val) { $val = trim($val); });

require '../inc/load.inc.php';

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php if (!empty(PAGE_TITLE)): echo htmlentities(PAGE_TITLE); endif; ?></title>
    <link rel="stylesheet" type="text/css" href="static/style.css" />
    <script type="text/javascript" src="static/script.js"></script>
    <script type="text/javascript" src="static/sorttable/sorttable.js"></script>
    <script type="text/javascript">sorttable.sort_alpha = function(a,b) { return a[0].localeCompare(b[0], 'fr'); }</script>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
<div id="menu">
    <ul>
            <li>Outils Dicare : <a href=".">Documentation</a> | <a href="https://github.com/envlh/dicare-tools">Sources</a> | <a href="https://www.wikidata.org/wiki/User:Envlh">Contact</a> | <a href="http://www.dicare.org/">dicare.org</a></li>
            <li>Noms de famille : <a href="homonymie.php">Génération d'une page d'homonymie</a>
            | <a href="nom-de-famille.php">Ajout en masse d'un nom de famille</a>
            | <a href="suggestions.php">Suggestions de noms de famille manquants</a>
            | Statistiques : <a href="departements.php">par département français</a>, <a href="pays.php">par pays</a></li>
            <li>Bateaux : <a href="bateaux.php">Listes</a></li>
    </ul>
</div>
