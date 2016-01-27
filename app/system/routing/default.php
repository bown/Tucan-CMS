<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */
	
	require 'app/system/routing/config.php';
	require 'app/system/routing/frontend.php';

	//Login
	$route->respond("/" . $backend . "/login", function ($request, $response, $service, $app) {
	    $render = new render();
	    $config = new config();

	    $render->render("admin.login", ["system" => $config->system, "get" => $_GET]);

	});

	//Logout
	$route->respond("/" . $backend . "/logout", function ($request, $response, $service, $app) {

		session_destroy();
		unset($_SESSION);

	    $response->redirect("login");

	});

	//Authenticate
	$route->respond("POST", "/". $backend . "/auth", function ($request, $response, $service, $app) {
		$redirect = 0;
		$auth = new user();

		$redirect = $auth->auth($request->username, $request->password);

		if(!$redirect) {
			$response->redirect("login?error=1");
		} else {
			$response->redirect("pages");
		}


	});

	//Pages
	$route->respond("/". $backend . "/pages", function ($request, $response, $service, $app) {
		
		$app->isUser;
		extension::includeAll();

	    $render = new render();
	    $config = new config();
	    $db = new db("pages");
	    $layouts = new db("layouts");
	    $all = $db->convert($db->all());
	    $layouts = $layouts->convert($layouts->all());

	    $render->render("admin.pages", ["system" => $config->system, "pages" => $all, "layouts" => $layouts]);

	});

	//Layouts
	$route->respond("/". $backend . "/layouts", function ($request, $response, $service, $app) {
		
		$app->isUser;
		extension::includeAll();

	    $render = new render();
	    $config = new config();
	    $db = new db("layouts");
	    $all = $db->convert($db->all());

	    if(isset($request->delete)) {
	    	$db->delete($request->delete);
	    }

	    $render->render("admin.layouts", ["system" => $config->system, "layouts" => $all]);

	});

	//Layouts: Create
	$route->respond("/". $backend . "/layouts/create", function ($request, $response, $service, $app) {
		
		$app->isUser;
		extension::includeAll();

	    $render = new render();
	    $config = new config();
	    $db = new db("components");
	    $layouts = new db("layouts");
	    $all = $db->convert($db->all());

	    $layouts = $layouts->convert($layouts->all());

	    $json = json_encode($all);

	    $render->render("admin.layouts.create", ["system" => $config->system, "layouts" => $layouts, "components" => $all, "json" => $json, "get" => $_GET]);

	});

	//Layouts: Save
	$route->respond("/". $backend . "/layouts/save", function ($request, $response, $service, $app) {
		
		$app->isUser;
		extension::includeAll();
		$config = new config();

		$post = (object)$_POST;

		if(!$post->title) {
			$response->redirect("create?error=1");
		} else {
			$layout = new layout();
			$publish = $layout->publish($_POST);
			if($publish) {
				$response->redirect("create?success=".$post->title);
			}
		}



	});

	//Pages: Create
	$route->respond("/". $backend . "/pages/create", function ($request, $response, $service, $app) {
		
		$app->isUser;
		extension::includeAll();

	    $render = new render();
	    $config = new config();
	    $db = new db("components");
	    $all = $db->sortForm($db->convert($db->all()));
	    $layout = "";

	   	if(isset($request->layout)) {
	   		$layouts = new db("layouts");
	   		$layout = $layouts->get($request->layout);
	   	} 

	    $json = json_encode($all);

	    $render->render("admin.pages.create", ["system" => $config->system, "layout" => $layout, "components" => $all, "json" => $json]);

	});

	//Components
	$route->respond("/". $backend . "/components", function ($request, $response, $service, $app) {
		
		$app->isUser;
		extension::includeAll();

	    $render = new render();
	    $config = new config();
	    $db = new db("components");
	    $all = $db->convert($db->all());


	    //Delete Component
	    if(isset($request->delete)) {
	    	$component = new component();
	    	$component->delete($request->delete);
	    }

	    $render->render("admin.components", ["system" => $config->system, "components" => $all]);

	});

	//Components:Ajax Post
	$route->respond("POST", "/". $backend . "/components/create", function ($request, $response, $service, $app) {
			
		$app->isUser;
		extension::includeAll();

		if(isset($_POST) 
			&& isset($request->title) 
			&& isset($request->filename) 
			&& !empty($request->title) 
			&& !empty($request->filename)) {
				
			$component = new component();
			$component->create($_POST);
		    $error = false;
		    $text = "";

		} else {
			$error = true;
			$text = "Unable to create component due to undefined value.";
		}

		die(json_encode(["response" => ["errors" => $error, "text" => $text]]));

	});

	//Variables
	$route->respond("/". $backend . "/variables", function ($request, $response, $service, $app) {
		
		$app->isUser;
		extension::includeAll();

	    $render = new render();
	    $config = new config();
	    $db = new db("variables");

	    if(isset($_POST['key']) && isset($_POST['value'])) {
	    	$db->set(str_replace(" ", "_", strtolower($_POST['key'])), $_POST['value']);
	    }

	    if(isset($_GET['delete'])) {
	    	$db->delete($_GET['delete']);
	    }

	    $render->render("admin.variables", ["system" => $config->system, "variables" => $db->all()]);

	});

	//Extensions
	$route->respond("/". $backend . "/extensions", function ($request, $response, $service, $app) {

		$app->isUser;
		extension::includeAll();

	    $render = new render();
	    $config = new config();
	    $db = new db("extensions");

	    if(isset($_POST['title']) && isset($_POST['filename']) && strpos($_POST['filename'], '.php') !== FALSE) {
	    	$db->set(str_replace(" ", "_", strtolower($_POST['title'])), $_POST['filename']);
	    }

	    if(isset($_GET['delete'])) {
	    	$db->delete($_GET['delete']);
	    }

	    $render->render("admin.extensions", ["system" => $config->system, "extensions" => $db->all()]);

	});


	$route->dispatch();

 ?>