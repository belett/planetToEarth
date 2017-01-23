<?php

require '../conf/conf.inc.php';

function __autoload($className) {
	include '../inc/lib/'.$className.'.class.php';
}

?>