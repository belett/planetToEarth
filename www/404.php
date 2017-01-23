<?php

header('HTTP/1.0 404 Not Found', true, 404);

define('PAGE_TITLE', 'Page introuvable');
require '../inc/header.inc.php';

?>

<h1>Page introuvable</h1>

<p>La page demandée n'existe pas.</p>

<?php

require '../inc/footer.inc.php';

?>