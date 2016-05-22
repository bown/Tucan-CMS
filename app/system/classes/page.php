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
			$pageLayout = "page_layout";

			$title = $arr[$pageTitle];
			$endpoint = $arr[$pageEnd];
			$layout = $arr[$pageLayout];

			unset($arr[$pageTitle]);
			unset($arr[$pageEnd]);
			unset($arr[$pageLayout]);

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
			$data["layout"] = $layout;
			$data["endpoint"] = $endpoint;

			$db = new db("pages");
			$db->set($title, $data);

			$this->savePage($title);

			return true;
		}

		function savePage($page) {
			$pages = new db("pages");
			$config = new config();
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . $config->system->evany->directory->path ."/cache/$page.html", $this->render($pages->get($page), true));
			return true;
		}

		function render($page, $save = false) {
			$render = new render();
			$components = new db("components");
			$keys = array_keys($page);
			$html = "";


			foreach($keys as $key) {
				if($key != "page" && $key != "endpoint" && $key != "layout") {
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
			$layout = isset($page['layout']) ? $page['layout'] : false;
			$variables = new db("variables");
			$pages = new db("pages");
			$layouts = new db("layouts");
			$headFoot = false;
			$array = array();
			$names = array();
			$values = array();



			unset($page['page']);
			unset($page['endpoint']);
			unset($page['layout']);

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
				$array["pages"] = $pages->all();

				if($layout) {
					$custom = $layouts->get($layout);
					$array['custom'] = array("js" => $custom['customjs'], "css" => $custom['customcss']);
				}

				if($render->exists("header.twig", "frontend") && $render->exists("footer.twig", "frontend")) {
					$headFoot = true;
					$html .= $render->render("layout/header.twig", $array, "frontend");
				}

				if(isset($item['template'])) {
					$html .= $render->render("component/" . $item['template'], $array, "frontend");
				} else {
					$html .= "<p>Component Not Found.</p>";
				}
				if($headFoot) {
					$html .= $render->render("layout/footer.twig", $array, "frontend");
				}
			}

			if($save) {
				return $html;
			}
			echo $html;
			die();
		}

	}

?>