<?php

	/* Blocks
	 * A CMS for front end developers
	 * Made with love by Jake Bown
	 * twitter.com/@jakebown1
	 * jakebown@gmail.com
	 */

	class template {

		function __construct() {

			//Backend
			$loader = new Twig_Loader_Filesystem('app/resources/backend/views');
			$this->be = new Twig_Environment($loader, array(
			    //'cache' => 'app/resources/backend/cache',
			));

			//Frontend
			$loader = new Twig_Loader_Filesystem('app/resources/frontend/views');
			$this->fe = new Twig_Environment($loader, array(
			    //'cache' => 'app/resources/frontend/cache',
			));
		}

		function frontend($template, $arr = []) {
			return $this->fe->render("$template.twig", $this->constructArray($arr));
		}

		function backend($template, $arr = []) {
			return $this->be->render("$template.twig", $this->constructArray($arr));
		}

		function constructArray($arr) {
			$db = new database("core");
			$arr['blocks'] = $_SERVER['blocks'];
			$arr['navigation'] = $db->select("navigation");
			if(isset($_SESSION['user'])) {
				$arr['user'] = $_SESSION['user'];
			} else {
				$arr['user'] = ["email" => "jake@blockscms.com"];
			}
			return $arr;
		}
	}
?>