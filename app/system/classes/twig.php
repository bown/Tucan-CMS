<?php
	/*
	 * Evany CMS
	 * @version 1.0.1
	 * @author Jake Bown <jakebown@gmail.com>
	 */
	
	class render {

		function __construct($be = "app/resources/backend/", $fe = "app/resources/frontend/") {

			$this->backend = new Twig_Environment(new Twig_Loader_Filesystem($be), array(
			    //'cache' => $be . 'layout/cache/',
			));

			$this->frontend = new Twig_Environment(new Twig_Loader_Filesystem($fe), array(
			    //'cache' => $fe . 'layout/cache/',
			));

		}

		function render($layout, $array, $output = "backend") {
			if($output == "backend") {

				$active = explode('/', $_SERVER['REQUEST_URI']);

				$active = isset($active[2]) ? $active[2] : false;

				if(strpos($active, "?") !== false) {
					$active = explode('?', $active)[0];
				}

				$array['activePage'] = strtolower($active);

				echo $this->backend->render("component/" . $layout . ".twig", $array);
			} else {
				echo $this->frontend->render("component/" . $layout . ".twig", $array);
			}
		}

	}
?>