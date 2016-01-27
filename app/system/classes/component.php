<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */

	class component {

		function delete($id) {
			$db = new db("components");
			$all = $db->convert($db->all());
			foreach($all as $a) {
	    		if($a['id'] == $id) {
	    			$db->delete($a['title']);
	    			return true;
	    		}
	    	}

	    	return false;
		}

		function create($post) {
			$db = new db("components");
		    $post['id'] = substr(sha1(rand(0,999)), 0, 8);
		    $db->set($post['title'], $post);
		    return true;
		}

	}

 ?>