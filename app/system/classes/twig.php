<?php
	/*
	 * Evany CMS
	 * @version 1.0.1
	 * @author Jake Bown <jakebown@gmail.com>
	 */
	
	class render {

		function __construct($be = "app/resources/backend/", $fe = "app/resources/frontend/") {

			$this->be = $be;
			$this->fe = $fe;
			$this->backend = new Twig_Environment(new Twig_Loader_Filesystem($be), array(
			    //'cache' => $be . 'layout/cache/',
			));

			$this->frontend = new Twig_Environment(new Twig_Loader_Filesystem($fe), array(
			    //'cache' => $fe . 'layout/cache/',
			));

		}

		function exists($file, $febe) {
			$dir = $this->be;
			if($febe == "frontend") {
				$dir = $this->fe;
			}
			if(file_exists($dir . "layout/" . $file)) {
				return true;
			} else {
				return false;
			}
		}

		function render($layout, $array, $output = "backend") {
			if($output == "backend") {

				$extensions = new extensions();

				$active = explode('/', $_SERVER['REQUEST_URI']);
				$activeDupe = $active;

				$active = isset($active[2]) ? $active[2] : false;

				if(strpos($active, "?") !== false) {
					$active = explode('?', $active)[0];
				}

				if($active == "extension") {
					$active = isset($activeDupe[3]) ? $activeDupe[3] : $active;
				}

				$array['activePage'] = strtolower($active);
				$array['extensions'] = $extensions->listAll(false);

				echo $this->backend->render("component/" . $layout . ".twig", $array);
			} else {
				$db = new db("variables");
				$config = new config();

				$array['config'] = $config->system->evany;	

				$array['variables'] = $db->all();
				if(strpos($layout, ".twig") !== FALSE) {
					$layout = $layout;
				} else {
					$layout = $layout . ".twig";
				}
				return $this->frontend->render($layout, $array);
			}
		}

	}
?>