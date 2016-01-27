<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */
	
	class user {

		function auth($username, $password) {
			$db = new db("users");

			$query = $db->get($username);
			if(is_array($query)) {

				if(sha1($password) == $query['password']) {
					$this->session($query);
					return true;
				} else {
					return false;
				}

			} else {
				return false;
			}
		}

		function session($arr) {
			$_SESSION['user'] = $arr;
		}
}

?>