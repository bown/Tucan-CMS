<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */

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