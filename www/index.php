<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>planet to Earth</title>
    <link rel="stylesheet" type="text/css" href="static/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
<?php

// TODO :
// ajouter cartes manquantes
// ajouter des langues voir https://wikitech.wikimedia.org/wiki/Help:Tool_Labs#Make_the_tool_translatable
// gérer les *fracking* coordonnées planétographiques/planétocentriques (attendre la propriété RCS ?)
// passer le style du SVG dans le fichier CSS ? (essayer avec http://codepen.io/)

define('PAGE_TITLE', 'Planet to Earth');
require '../inc/load.inc.php';

$availableLanguages = ["en","fr","br"] ;
$lang = isset( $_GET['lang']) ? $_GET['lang'] : $availableLanguages[0] ;
// test
if ( ! in_array ($lang, $availableLanguages)) {
	$lang = $availableLanguages[0] ;
}

$availableBodies = ["Q111","Q308","Q313","Q405","Q2565","Q3123","Q3169"] ;
$id = isset( $_GET['id']) ? $_GET['id'] : $availableBodies[0] ;
// test
if ( ! in_array ($id, $availableBodies)) {
	$id = $availableBodies[0] ;
}

echo '<h1>Planet to Earth - links in space</h1>' ;

switch ($lang) {
    case "fr" :
        echo "<p>Cet outil utilise les données <a href=http://wikidata.org/>Wikidata</a> pour visualiser les liens entre les lieux sur un corps astronomique nommés en référence à un lieu situé sur la planète <a href=https://fr.wikipedia.org/wiki/Terre>Terre</a>.</p>";
	$labelLang = "Choix d’un corps astronomique";
        break;
    case "en":
        echo "<p>This tool use data from <a href=http://wikidata.org/>Wikidata</a> to visualise the links between places on an astronomical body named after a place on planet <a href=https://en.wikipedia.org/wiki/Earth>Earth</a>.</p>";
        $labelLang = "Choose an astronomical body";
		break;
    case "br":
        echo "<p>An benveg-mañ a implij roadoù eus <a href=http://wikidata.org/>Wikidata</a> evit diskouez liammoù etre lec'hioù war ur c'horf anvet diwar ul lec'h an planedenn <a href=https://br.wikipedia.org/wiki/Douar>Douar</a>.</p>";
        $labelLang = "Dibab ur c’horf";
		break;
}

echo '
<form method="get" >
<label for="lang">Lang</label> :
<select name="lang">
<option value="en"' . ( $lang == 'en' ? ' selected="selected"' : '' ) . '>English</option>
<option value="fr"' . ( $lang == 'fr' ? ' selected="selected"' : '' ) . '>français</option>
<option value="br"' . ( $lang == 'br' ? ' selected="selected"' : '' ) . '>brezhoneg</option>
</select>';

echo '<label for="id">'.$labelLang.'</label> :<br />
<select name="id">
<option value="Q111"' . ( $lang == 'Q111' ? ' selected="selected"' : '' ) . '>Mars</option>
<option value="Q308"' . ( $lang == 'Q308' ? ' selected="selected"' : '' ) . '>Mercure</option>
<option value="Q313"' . ( $lang == 'Q313' ? ' selected="selected"' : '' ) . '>Vénus</option>
<option value="Q405"' . ( $lang == 'Q405' ? ' selected="selected"' : '' ) . '>Lune</option>
<option value="Q2565"' . ( $lang == 'Q2565' ? ' selected="selected"' : '' ) . '>Titan</option>
<option value="Q3123"' . ( $lang == 'Q3123' ? ' selected="selected"' : '' ) . '>Io</option>
<option value="Q3169"' . ( $lang == 'Q3169' ? ' selected="selected"' : '' ) . '>Ganymède</option>
</select>
<input type="submit" value="Go" />
</form>';

// requete SPARQL
$query = '
SELECT ?item ?itemLabel ?cPlanet ?origin ?originLabel ?cEarth WHERE {
	?item wdt:P376 wd:'.$id.' ; wdt:P625 ?cPlanet ; wdt:P138 ?origin .
	minus { ?origin wdt:P376 [] } .
	?origin wdt:P625 ?cEarth .
	SERVICE wikibase:label { bd:serviceParam wikibase:language "'.$lang.'" }
}';

try { $items = sparql::query($query); }
	catch ( Exception $e ) {
	echo '<p>Problem, no results available.</p>';
}
	
	if (count($items->results->bindings) === 0) {
		echo '<p>No results.</p>';
    }
    else {
		echo '<p>'.count($items->results->bindings).' links found for <a href="https://www.wikidata.org/wiki/'.$id.'">'.$id.'</a></p>';
		echo '<div style="text-align: center"><svg width="1000" height="1000" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
	<style>
	circle {
		fill: #A00;
	}
	line {
	    opacity: 0.4;
		stroke-width:3;
		stroke: #A33;
	}
	  line:hover {
	    opacity: 0.8;
		stroke-width:5;
		stroke: #A00;
	}
	</style>' ;
	echo '<defs><circle id="spot" cx="0" cy="0" r="2.5" /></defs>
<image x="0" y="0" width="1000" height="500" xlink:href="';
	switch ($id) {
		case "Q111" :
			echo "https://upload.wikimedia.org/wikipedia/commons/b/b7/Mars_G%C3%A9olocalisation.jpg";
			break;
		case "Q308":
			echo "https://upload.wikimedia.org/wikipedia/commons/f/f2/Mercury_global_map_2013-05-14_bright.png";
			break;
		case "Q313":
			echo "https://upload.wikimedia.org/wikipedia/commons/7/72/Venus_map_NASA_JPL_Magellan-Venera-Pioneer.jpg";
			break;
		case "Q405":
			echo "https://upload.wikimedia.org/wikipedia/commons/d/db/Moonmap_from_clementine_data.png";
			break;
		case "Q2565":
			echo "https://upload.wikimedia.org/wikipedia/commons/c/cf/Map_of_Titan_cropped.jpg";
			break;
		case "Q3123":
			echo "https://upload.wikimedia.org/wikipedia/commons/4/48/Io_for_GeoHacks.jpg";
			break;
		case "Q3169":
			echo "https://upload.wikimedia.org/wikipedia/commons/4/43/Map_of_Ganymede_by_Bj%C3%B6rn_J%C3%B3nsson_and_centered_by_Feldo.jpg";
			break;
	}
echo '" /><image x="0" y="500" width="1000" height="500" xlink:href="https://upload.wikimedia.org/wikipedia/commons/a/ac/Earthmap1000x500.jpg" />';
	foreach ($items->results->bindings as $item) {
		#vérification du globe utilisé par les coordonnées
//		if ( substr($item->cPlanet->value, 32, -20) != $id ) {
//			echo '<a xlink:href="'.$item->item->value.'?uselang='.$lang.'"><text x="5" y="20">'.($item->itemLabel->value).'</text></a>' ;
//		}
		$coordPlanet = explode(' ', substr($item->cPlanet->value, 44, -1)) ;
		#calcul des coordonnées pour la planète ; attention aux coords planétographiques/planétocentriques...		
		if ($coordPlanet[0] < 180) {
                $coordPlanet[0] = ( $coordPlanet[0] / 0.36 ) + 500 ;
            } else {
                $coordPlanet[0] = ( $coordPlanet[0] / 0.36 ) - 500 ;
            }
		$coordPlanet[1] = 250 - $coordPlanet[1] / 0.36 ;
		#calcul des coordonnées pour la Terre
		$coordEarth = explode(' ', substr($item->cEarth->value, 6, -1)) ;
			$coordEarth[0] = ( $coordEarth[0] / 0.36 ) + 500 ;
		$coordEarth[1] = 750 - ( $coordEarth[1] / 0.36 ) ;
		echo '<line x1="'.$coordPlanet[0].'" y1="'.$coordPlanet[1].'" x2="'.$coordEarth[0].'" y2="'.$coordEarth[1].'" />
		<a xlink:href="'.$item->item->value.'?uselang='.$lang.'"><text x="'.($coordPlanet[0]+5).'" y="'.$coordPlanet[1].'">'.($item->itemLabel->value).'</text></a>
		<a xlink:href="'.$item->origin->value.'?uselang='.$lang.'"><text x="'.$coordEarth[0].'" y="'.$coordEarth[1].'">'.($item->originLabel->value).'</text></a>
		<use xlink:href="#spot" x="'.$coordPlanet[0].'" y="'.$coordPlanet[1].'" />
		<use xlink:href="#spot" x="'.$coordEarth[0].'" y="'.$coordEarth[1].'" />' ;
	}
//à traduire, donner le lien direct vers GitHub
	echo '</svg></div>
<p>Caveat : cet outil peut avoir des bugs, n’hésitez pas à me les signaler ainsi que tout suggestion d’amélioration.</p>
<p>Outil par <a href="https://www.wikidata.org/wiki/User:VIGNERON">Nicolas Vigneron</a>, 2016 (special thanks to <a href="https://www.wikidata.org/wiki/User:Envlh">Envlh</a>, <a href="https://www.wikidata.org/wiki/User:Arkanosis">Arkanosis</a>, <a href="https://www.wikidata.org/wiki/User:Jean-Frédéric">Jean-Frédéric</a> and <a href="https://www.wikidata.org/wiki/User:Seb35">Seb35</a>) ; disponible sur <a href="https://github.com/belett/planetToEarth">ma page GitHub</a>.</p>';
	}
?>
</body>
</html>
