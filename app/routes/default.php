<?php

/* Blocks
 * A CMS for front end developers
 * Made with love by Jake Bown
 * twitter.com/@jakebown1
 * jakebown@gmail.com
*/

$backend = $_SERVER['tucan']['backend'];

$route->respond("GET", "/$backend", function () {
    $db = new database("core");
    $db->insert([
    	"navigation" => ["Dashboard" => "/", "Pages" => "/pages", "Components" => "/components", "Variables" => "/variables", "Users" => "/users", "Settings" => "/settings"]
    ]);

    $template = new template();
    echo $template->backend('dashboard');
});

$route->respond('/[:page]', function($request) {
    $db = new database("page");
    $page = $db->select($request->page);
    if(!is_array($page)) {

    } else {
        $db = new database("blocks");
        $template = new template();
        $include = [];
        $body;

        if($page['page']['published'] != "published") {
            $error = new errors();
            $error->debug(["Page" => "Page is not published"]);
        }

        if(isset($page['component'])) {
            foreach($page['component'] as $p) {
                $component = $db->component($p['component']);  
                $includes = $component['includes'];
                
                foreach($includes['js'] as $js) {
                    $include['js'][] = $js;
                }

                foreach($includes['css'] as $css) {
                    $include['css'][] = $css;
                }
                $body[$p['widget']][] = $template->frontend($component['layout'], ["component" => $p, "tucan" => $_SERVER['tucan']]);
            }
        }


        $header = $template->frontend("header", ["includes" => $include, "tucan" => $_SERVER['tucan']]);
        $footer = $template->frontend("footer", ["includes" => $include, "tucan" => $_SERVER['tucan']]);
        
        echo $header;

        if(isset($body)) {
            sort($body); //Sort by widget

            foreach($body as $item) {
                echo $item[0];
            }
        }

        echo $footer;


    }
});

$route->respond("GET", "/$backend/pages", function() {
    $template = new template();
    $db = new database("page");
    echo $template->backend('pages', ["pages" => $db->selectAll()]);
});


$route->respond("GET", "/$backend/variables", function() {
    $template = new template();
    $db = new database("vars"); 
    echo $template->backend('variables', ["vars" => $db->selectAll()]);
});

$route->respond("GET", "/$backend/users", function() {
    $template = new template();

    $db = new database("users");
    $db->insert([
        "admin" => ["type" => "super", "username" => "admin", "name" => "Chuck Norris", "password" => sha1("password1"), "email" => "admin@example.com"]
    ]);
    echo $template->backend('users', ["users" => $db->selectAll()]);
});



$route->respond("GET", "/$backend/pages/edit", function() {
    if(isset($_GET['page'])) {
        $template = new template();
        $db = new database("page");
        echo $template->backend('pages', ["pages" => $db->selectAll()]);
    }
});


$route->respond("GET", "/$backend/pages/new", function() {
    $pages = new database("blocks");

    $name = (isset($_GET['name']) ? $_GET['name'] : false);

	$db = new database("blocks");
    $db->insert([
    	"components" => [
    		[
    			"title" => "Block Text",
                "slug" => "block_text",
    			"inline" => "false",
    			"link" => "blockText",
    			"includes" => ["css" => ["blocks.css", "blocks.colors.css"], "js" => ["blocks.js"]],
                "layout" => "blocktitle",
    			"fields" => ["Header" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Subtitle" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Button" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Link" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"]],
    			"candelete" => true
    		],
            [
                "title" => "Hero Header",
                "slug" => "hero_header",
                "inline" => "false",
                "link" => "heroHeader",
                "includes" => ["css" => ["hero.css", "hero.carousel.css"], "js" => ["hero.js"]],
                "layout" => "hero",
                "fields" => ["Header" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Subtitle" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Button" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"], "Link" => ["type" => "text", "limit" => ["max" => 5, "min" => 32], "required" => "true"]],
                "candelete" => true
            ]
    	]
    ]);

	$template = new template();
    echo $template->backend('newpage', ["name" => $name, "components" => $db->select("components")]);
});

?>