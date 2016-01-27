<?php
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */

 //Secure Sessions
 session_start();


 //Composer 
 require 'app/vendor/autoload.php';

 //Helpers
 require 'app/system/helper/klein.php';

 //Libraries
 require 'app/system/library/flatdb.php';
 require 'app/system/library/sessions.php';

 //Config
 require 'app/system/classes/config.php';
 require 'app/system/classes/user.php';
 require 'app/system/classes/database.php';
 require 'app/system/classes/extension.php';
 require 'app/system/classes/component.php';
 require 'app/system/classes/layout.php';
 require 'app/system/classes/page.php';
 require 'app/system/application/config.php';

 //Classes
 require 'app/system/classes/twig.php';

 //Routes
 require 'app/system/routing/default.php';

?>