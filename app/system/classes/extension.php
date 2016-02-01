<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */
	
 class extensions {

 	function listAll() {
 		$db = new db("extensions");
 		return $db->all();
 	}
 }

 // static function includeAll() {
 	// 	$db = new db("extensions");
 	// 	$extend = new extend();
  //   	$extensions = $db->convert($db->all());
  //   	foreach($extensions as $extension) {
  //   		if(file_exists("app/system/extensions/$extension")) {
  //   			require "app/system/extensions/$extension";
  //   		}
  //   	}
 	// }

 class tucan {

 	static function register($func = false, $endpoint = false, $title = false) {

 	}


 }

 class extend {

 	function __construct() {
 		$this->variables = [];
 	}

 	function register($func) {
 		$this->variables[] = $func();
 	}

 }

?>