<?php
session_start();
/* Blocks
 * A CMS for front end developers
 * Made with love by Jake Bown
 * twitter.com/@jakebown1
 * jakebown@gmail.com
*/
	
require 'database.php';
require '../lib/database/database.php';

 class ajax {

 	function newPage($data) {

 		if(isset($data)) {

 			$db = new database("page");
			$data = json_decode($data);

			if(substr($data->name, 0, 1) == "/") {
				$post['url'] = $data->name;
			} else {
				$post['url'] = "/$data->name";
			}

			$post['title'] = str_replace(" ", "-", strtolower($data->title));
			$post['published'] = $data->published;

			$tag = ["<tucan-component>", "</tucan-component>"];


			foreach($data as $item) {
				$item = str_replace($tag,null,$item);
				$item = explode('|', $item);
				$count = 0;
				foreach($item as $val) {
					$val = explode('~~', $val);
					if(isset($val[1])) {
						$x[$val[0]][] = $val[1];
					}
				}
				$count++;
			}

			if(isset($x)) {
				foreach ($x as $key => $value) {
					$count = 0;
					foreach($value as $v) {
						$components[$count][$key] = $v;
						$count++;
					}
				}	
			}

			$count = 0;
			if(isset($components)) {
				foreach($components as $component) {
					$ar["page"] = $post;
					$ar["component"][] = $component;

					$db->db->set($post['title'], $ar);
					$count++;
				} 
			} else {
				$ar["page"] = $post;
				$db->db->set($post['title'], $ar);
			}


 		} else {
 			die(json_encode(false));
 		}
 	}

 	function newVariable($post) {
 		if(isset($post['key']) && isset($post['value'])) {
 			$db = new database("vars");
 			$db->db->set($post['key'], $post['value']);
 			return json_encode(true);
 		} else {
 			return json_encode(false);
 		}
 	}
 }

 $ajax = new ajax();

 if(isset($_GET['type']) && isset($_POST['data'])) {
 	 $ajax = new ajax();
 	 switch ($_GET['type']) {
 	 	case 'page':
 	 		return $ajax->newPage($_POST['data']);
 	 		break;

 	 	case 'variable':
 	 		return $ajax->newVariable($_POST['data']);
 	 		break;
 	 	
 	 	default:
 	 		# code...
 	 		break;
 	 }
 } else {
 	return json_encode(false);
 }

?>