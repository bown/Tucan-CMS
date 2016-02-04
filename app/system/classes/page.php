<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */

	class page {

		function publish($arr) {

			/*
			 * [key]-[name]-[component]
			 */

			$data = [];

			$pageTitle = "page_title";
			$pageEnd = "page_endpoint";

			$title = $arr[$pageTitle];
			$endpoint = $arr[$pageEnd];

			unset($arr[$pageTitle]);
			unset($arr[$pageEnd]);

			//print_r($arr);

			$layouts = new db("layouts");

			$count = 0;
			$compCount = 0;

			foreach($arr as $key => $val) {
				$expand = explode('-', $key);
				$id = $expand[0];
				$name = $expand[1];
				$count++;
				$component = $expand[2];

				$data[$component][$count] = [
					"name" => $name,
					"value" => $val,
					"id" => $id,
					"total" => count($layouts->get($component))
				];

			}

			if(substr($title, 0, 1) == "@") {
				$endpoint = "/";
				$title = substr($title, 1, strlen($title));
			}

			$data["page"] = $title;
			$data["endpoint"] = $endpoint;

			$db = new db("pages");
			$db->set($title, $data);

			return true;
		}

		function render($page) {
			$render = new render();
			$components = new db("components");
			$keys = array_keys($page);
			$html = "";

			foreach($keys as $key) {
				if($key != "page" && $key != "endpoint") {
					$strkey = str_replace("_", " ", $key);
					$query = $components->get($strkey);
					if(is_array($query)) {
						$page[$key]["template"] = $query['filename'];
						$page[$key]["key"] = $key;
					}
				}
			}

			$title = $page['page'];
			$endpoint = $page['endpoint'];
			$variables = new db("variables");
			$array = array();
			$names = array();
			$values = array();

			unset($page['page']);
			unset($page['endpoint']);

			foreach($page as $item) {
				foreach($item as $k => $x) {
					if(is_array($x)) {
						foreach($x as $n => $v) {
							if($n == "name") {
								$names[] = $v;
							}

							if($n == "value") {
								if($v == "on") {
									$v = true;
								}
								$values[] = $v;
							}
						}
					}
				}

				$array = array_combine($names, $values);

				$array["variables"] = $variables->all();


				$html .= $render->render($item['template'], $array, "frontend");
			}

			echo $html;
		}

	}

?>