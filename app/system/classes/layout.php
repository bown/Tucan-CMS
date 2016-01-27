<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */

	class layout {

		function publish($arr) {
			$component = "comp";
			$layout = ["title" => "", "components" => [], "customjs" => "", "customcss" => ""];
			if(!is_array($arr)) {
				return false;
			}
			foreach($arr as $key => $val) {
				if(substr($key, 0, 4) == $component) {
					$layout["components"][] = $val;
				} else {
					$layout[$key] = $val;
				}
			}

			$db = new db("layouts");
			$db->set($layout["title"], $layout);

			return true;
		}

	}

?>