<?php
	/*
	 * Evany CMS
	 * @version 1.0.1
	 * @author Jake Bown <jakebown@gmail.com>
	 */
	
	class config {

		function __construct($json = CONFIG) {
			$parse = json_decode(file_get_contents($json));
			$this->system = $parse;
		}

	}

?>