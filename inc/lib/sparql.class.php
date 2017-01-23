<?php

class sparql {
    
    private static $queries = array();
    
    private static function getCacheFilename($query) {
        return SPARQL_CACHE_DIR.md5($query).'.dat';
    }
    
    public static function query($query, $cache = 0) {
        self::$queries[] = $query;
        $cacheFilename = self::getCacheFilename($query);
        clearstatcache();
        if (($cache > 0) && file_exists($cacheFilename) && (filemtime($cacheFilename) >= time() - $cache)) {
            $data = @file_get_contents($cacheFilename);
        } else {
            $data = @file_get_contents('https://query.wikidata.org/bigdata/namespace/wdq/sparql?format=json&query='.urlencode($query));
            if ($data === false) {
                throw new Exception('Erreur à l\'exécution de la requête SPARQL.'."\n".$query);
            }
            if ($cache > 0) {
                file_put_contents($cacheFilename, $data);
            }
        }
        return json_decode($data);
    }
    
    public static function getQueryTime($query) {
        $cacheFilename = self::getCacheFilename($query);
        clearstatcache();
        if (file_exists($cacheFilename)) {
            return filemtime($cacheFilename);
        }
        return null;
    }
    
    public static function getQueries() {
        return self::$queries;
    }

}

?>