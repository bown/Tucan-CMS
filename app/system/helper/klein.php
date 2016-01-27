<?php
/* Klein fix for request URL
 * by brettalton
 * https://github.com/chriso/klein.php/issues/176
 */

$base  = dirname($_SERVER['PHP_SELF']);

// Update request when we have a subdirectory
if (ltrim($base, '/')) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($base));
}

?>