<?php

/* Blocks
 * A CMS for front end developers
 * Made with love by Jake Bown
 * twitter.com/@jakebown1
 * jakebown@gmail.com
*/

$backend = $_SERVER['blocks']['backend'];

$route->respond("GET", "/$backend", function () {
    $db = new database("core");
    $db->insert([
    	"navigation" => ["Dashboard" => "/", "Pages" => "/pages", "Components" => "/components", "Variables" => "/variables", "Settings" => "/settings"]
    ]);

    $template = new template();
    echo $template->backend('dashboard');
});

$route->respond("GET", "/$backend/pages", function() {
	$template = new template();
    //print_r($_SERVER['blocks']['site_base']);
    echo $template->backend('pages');
});

$route->respond("GET", "/$backend/pages/new", function() {
	$db = new database("blocks");
    $db->insert([
    	"components" => [
    		[
    			"title" => "Block Text",
    			"inline" => "false",
    			"link" => "blockText",
    			"includes" => "false",
    			"fields" => ["Header" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Subtitle" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Button" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Link" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"]],
    			"slug" => "block_text",
    			"candelete" => true
    		],
    		[
    			"title" => "Header",
    			"inline" => "false",
    			"link" => "header",
    			"includes" => "false",
    			"fields" => ["Header" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Subtitle" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Button" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Link" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"]],
    			"slug" => "header",
    			"candelete" => false
    		],
    		[
    			"title" => "Footer",
    			"inline" => "false",
    			"link" => "footer",
    			"includes" => "false",
    			"fields" => ["Header" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Subtitle" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Button" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Link" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"]],
    			"slug" => "footer",
    			"candelete" => false
    		]
    	]
    ]);

	$template = new template();
    echo $template->backend('newpage', ["components" => $db->select("components")]);
});

?>