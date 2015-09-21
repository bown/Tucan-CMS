<?php

	class globals {

		function __construct() {
			$blockfile = "app/config/blocks.json";
			$blockfile = json_decode(file_get_contents($blockfile));

			foreach ($blockfile->blocks as $key => $value) {
				$_SERVER['blocks'][$key] = $value;
				$_SESSION['blocks'][$key] = $value;
			}
		}
	}

	$global = new globals();

?>