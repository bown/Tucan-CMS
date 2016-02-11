<?php
	
	$route = new \Klein\Klein();
	$config = new config();
	$backend = $config->system->evany->backend;

	$route->respond(function ($request, $response, $service, $app) {

	    $app->register('isUser', function() {
	    	$render = new render();
	    	$config = new config();
	    	if(!isset($_SESSION['user'])) {
	    		$render->render("admin.notice", ["system" => $config->system]);
	    		die();
	    	}
	    });
	});

	//Error 
	$route->onHttpError(function ($code, $router) {
		/*
		 * HttpError doesn't have access to
		 * vars outside of here.
		 */		

		$render = new render();
	    $config = new config();
	    $build = new page();
	    $pages = new db("pages");


	    $request = $_SERVER['REQUEST_URI'];
	    $found = false;

	    foreach($pages->all() as $page) {
	    	$component = array_keys($page)[0];
	    	if($page["endpoint"] != "/") {
	    		//Add slash to URL
	    		$page["endpoint"] = "/" . $page["endpoint"];
	    	}
	    	if($page["endpoint"] == $request) {
	    		$found = $page;
	    	}
	    }



	    if($found) {
	    	$build->render($found);
	    } else {
	    	$render->render("admin.error", ["system" => $config->system]);
	    }

	    die();		
	});

?>