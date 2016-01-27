<?php
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */

 //Debugging and Error Reporting

 $config = new config();
 $debugReport = 0;

 $debug = $config->system->evany->debugging;

 if($debug == "true") {
 	$debugInt = 1;
 } else {
 	$debugInt = 0;
 }

 if($debugInt) {
 	$debugReport = E_ALL;
 }


 ini_set('display_errors', $debugInt);
 ini_set('display_startup_errors', $debugInt);
 error_reporting($debugReport);