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

			foreach($arr as $key => $val) {
				$expand = explode('-', $key);
				$id = $expand[0];
				$name = $expand[1];
				$component = $expand[2];

				if(isset($data[$component][$name])) {
					$data[$component][] = [
						"name" => $name,
						"value" => $val,
						"id" => $id
					];
				} else {

					$data[$component] = [
						"name" => $name,
						"value" => $val,
						"id" => $id
					];
				}

			}

			$data["page"] = $title;
			$data["endpoint"] = $endpoint;

			$db = new db("pages");
			$db->set($title, $data);

			return true;
		}

	}

?>