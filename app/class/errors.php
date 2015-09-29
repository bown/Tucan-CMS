<?php
/* Blocks
 * A CMS for front end developers
 * Made with love by Jake Bown
 * twitter.com/@jakebown1
 * jakebown@gmail.com
*/

class errors {

	function debug($arr) {

		if($_SERVER['tucan']['debug']) {

			$head = "<style>
							html,body {width: 100%; background: #272821;}
							.errors-container { position: relative; }
							.errors { display: inline-block; padding: 10px 25px; color: #fff; }
							.errors p { color: #fff; font-family: Monospace; } 
							.errors p span:first-child { color: #6FD8E5; }
							.errors h1 { text-align: left; font-weight: 100; color: #EB2D69; font-family: Monospace; font-size: 13px; }</style>";
			
			$e = "<h1>Tucan Debug</h1>";

			$arr['Tucan'] = "You are seeing this because the site administrator has debugging turned on.";

			foreach($arr as $k => $v) {
				$e .= "<p><span>$k:</span> <span>$v</span></p>";
			}

			$html = $head . "<div class=\"errors-container\"><div class=\"errors\">$e</div></div>";
			die($html);
		}
	}

}

?>