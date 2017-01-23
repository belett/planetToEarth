<?php

$queries = sparql::getQueries();
if (count($queries) >= 1) {
    echo '<h2>Requête'.((count($queries) >= 2) ? 's' : '').' SPARQL</h2>';
    $i = 0;
    foreach ($queries as $query) {
        echo '<h3>#'.(++$i).' [<a href="https://query.wikidata.org/#'.rawurlencode($query).'">WDQS</a>]</h3><pre>'.htmlentities(trim($query)).'</pre>';
    }
}

?>
</body>
</html>
