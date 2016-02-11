<?php 
	/*
	 * Evany CMS
	 * @version 1.0.1
	 * @author Jake Bown <jakebown@gmail.com>
	 */

	class db {

		function __construct($db = false) {
			if($db) {
				$this->db = new FlatFileDB(array(
				    'db_file' => 'app/system/database/'.$db.'.db',
				    'db'      => $db,
				    'cache'   =>  true
				));
			}
		}

		function set($key, $val) {
			$this->db->set($key, $val);
		}

		function get($key) {
			return $this->db->get($key);
		}

		function delete($key) {
			$this->db->delete($key);
		}

		function all() {
			$arr = [];
			$dump = $this->db->get_keys();
			foreach($dump as $key => $val) {
				$arr[$val] = $this->get($val);
			}
			return $arr;
		}

		function editForm($arr) {
			$component = array_keys($edit)[0];
			
		}

		function sortForm($arr) {

			$data = [];
			$count = 0;

			for($i=0; $i < count($arr); $i++) { 

				foreach($arr[$i] as $key => $val) {

					if(isset($arr[$i]["fieldname-" . $count]) && isset($arr[$i])) {
						$value = false;
						$name = $arr[$i]["fieldname-" . $count];

						if(isset($arr[$i]["fieldtype-" . $count]) && $arr[$i]["fieldtype-" . $count] == "comma" && strpos($arr[$i]["fieldname-" . $count], "|") !== false) {
							$value = explode("|", $arr[$i]["fieldname-" . $count]);
							$name = $value[0];
							$value = explode(",", $value[1]);
						}

						$data[$arr[$i]['title']][] = [
							"name" => $name,
							"required" => isset($arr[$i]["fieldrequired-" . $count]) ? false : true,
							"type" => isset($arr[$i]["fieldtype-" . $count]) ? $arr[$i]["fieldtype-" . $count] : "input",
							"value" => $value, 
							"id" => substr(sha1(rand(0,99)), 0, 16)
						];
					}


					$count++;
				}

				$count = 0;
			}

			return $data;
		}

		function convert($assoc, $level = 1) {
			$converted = [];
		    foreach($assoc as $key => $val) {
		    	if($level > 1) {
		    		if(!is_array($key)) {
		    			$converted[] = $key;
		    		}
		    	} else {
		    		$converted[] = $assoc[$key];
		    	}
		    }
		    return $converted;
		}

	}

?>