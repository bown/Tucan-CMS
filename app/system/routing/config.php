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
	    //$extension = new extension();

	    echo str_replace("/" . $config->system->evany->backend, false, $_SERVER['REQUEST_URI']);

	    $render->render("admin.error", ["system" => $config->system]);
	    		die();
		
	});

?>