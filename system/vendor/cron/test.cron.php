<?php
$time = date("G:i:s");
$entry = "Información guardada a las $time.\n";

require_once(str_replace('//','/',dirname(__FILE__).'/') .'../../../admin/config.php');

$file = DIR_SYSTEM . "vendor/cron/test.cron.txt";

$open = fopen($file,"a");
 
if ( $open ) {
	fwrite($open,$entry);
	fclose($open);
}


?>