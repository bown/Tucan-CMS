<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */
	
 class extensions {

 	function listAll($template = false) {
 		$db = new db("extensions");
 		$extensions = array();
 		foreach($db->all() as $key => $extend) {
 			if(file_exists("app/system/extensions/$extend") && file_exists("app/resources/backend/extensions/$key.twig") || !$template) {
 				$extensions[$key] = $extend;   			
	   		}
 		}
 		return $extensions;
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