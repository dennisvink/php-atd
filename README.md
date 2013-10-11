php-atd
=======

PHP class to interface with After the Deadline (AtD) servers.

Usage:

<?php

require_once "php-atd.php";

$atd = new AtD();	// Parameters are optional, by default it will look for an AtD server running locally.
					// Optional parameters: AtD($host, $port, $apikey, $ssl)

$result1 = $atd->checkDocument('This iz a sample text wit, errorz to check.');
$result2 = $atd->checkGrammar('This iz a sample text wit, errorz to check.');

print_r($result1);
print_r($result2);

?>