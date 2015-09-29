<?php
	
	class globals {

		function __construct() {
			$blockfile = "app/config/tucan.json";
			$blockfile = json_decode(file_get_contents($blockfile));

			foreach ($blockfile->tucan as $key => $value) {
				$_SERVER['tucan'][$key] = $value;
				$_SESSION['tucan'][$key] = $value;
			}
		}

		function globals($vars) {
			if($vars) {
				foreach($vars as $key => $value) {
					$_SERVER['tucan']['global'][$key] = $value;
					$_SESSION['tucan']['global'][$key] = $value;
				}
			}
		}	
	}

	$global = new globals();

?>