<?php

class page {
    
    public static function getParameter($name, $default = null) {
        if (!empty($_POST[$name])) {
            return $_POST[$name];
        } elseif (!empty($_GET[$name])) {
            return $_GET[$name];
        } else {
            return $default;
        }
    }
    
}

?>