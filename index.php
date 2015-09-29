<?php
session_start();

/* Blocks
 * A CMS for front end developers
 * Made with love by Jake Bown
 * twitter.com/@jakebown1
 * jakebown@gmail.com
*/

//Config
require 'app/config/self.php';
require 'app/config/globals.php';

//Core
require 'vendor/autoload.php';
require 'app/lib/database/database.php';

//Dependencies
require 'app/class/database.php';
require 'app/class/template.php';
require 'app/class/errors.php';

$db = new database("vars");
$globals = new globals();
$globals->globals($db->selectAll());

//Setup Klein
$route = new \Klein\Klein();

//Routes
require 'app/routes/default.php';

//And, go!
$route->dispatch();
?>