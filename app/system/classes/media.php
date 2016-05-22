<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */
	
 class media {

 	function __construct() {
 		$config = new config();
 		$this->folder = "app/media/";
 	}

 	function scan($dir = false) {
 		$this->dir = isset($dir) ? $this->folder . $dir : $this->folder;

 		$omit = array(".", "..");
 		$scan = scandir($this->dir);

 		if(empty($scan)) {
 			return array('empty directory');
 		}

 		foreach($scan as $key => $dir) {
 			if(in_array($dir, $omit)) {
 				unset($scan[$key]);
 			}
 		}

 		return $scan;

 	}

 }