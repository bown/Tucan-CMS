<?php
/* Blocks
 * A CMS for front end developers
 * Made with love by Jake Bown
 * twitter.com/@jakebown1
 * jakebown@gmail.com
*/
	
require 'database.php';
require '../lib/database/database.php';

 class ajax {

 	function __construct() {

 		file_put_contents("file.txt", json_encode($_POST['data']));
 		echo $_POST['data'];
 		if(isset($_POST['data'])) {

 			$db = new database("app");
    		$db->insert($_POST['data']);
    		die(json_encode($_POST['data']));
    		
 		} else {
 			die(json_encode(false));
 		}
 	}
 }

 $ajax = new ajax();

?>