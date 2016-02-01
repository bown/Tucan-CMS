<?php 
/*
 * Evany CMS
 * @version 1.0.1
 * @author Jake Bown <jakebown@gmail.com>
 */
	
	require 'app/system/routing/config.php';
	require 'app/system/routing/frontend.php';


	$route->with("/" . $backend, function () use ($route, $backend) {

		$route->respond('GET', "/", function ($request, $response) {
	        $response->redirect("pages");
	    });

		//Login
		$route->respond("/login", function ($request, $response, $service, $app) {
		    $render = new render();
		    $config = new config();

		    $render->render("admin.login", ["system" => $config->system, "get" => $_GET]);

		});

		//Logout
		$route->respond("/logout", function ($request, $response, $service, $app) {

			session_destroy();
			unset($_SESSION);

		    $response->redirect("login");

		});

		//Authenticate
		$route->respond("POST", "/auth", function ($request, $response, $service, $app) {
			$redirect = 0;
			$auth = new user();

			$redirect = $auth->auth($request->username, $request->password);

			if(!$redirect) {
				$response->redirect("login?error=1");
			} else {
				$response->redirect("pages");
			}


		});

		//Users
		$route->respond("/users", function ($request, $response, $service, $app) {
			
			$app->isUser;
			

		    $render = new render();
		    $config = new config();
		    $db = new db("users");
		    $all = $db->convert($db->all());
		    $count = count($all);

		    if(isset($request->delete) && count($all) > 1) {
		    	$db->delete($request->delete);
		    }

		    $render->render("admin.users", ["system" => $config->system, "users" => $all, "count" => $count, "me" => $_SESSION['user']]);

		});

		//Layouts
		$route->respond("/layouts", function ($request, $response, $service, $app) {
			
			$app->isUser;
			

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
		$route->respond("/layouts/create", function ($request, $response, $service, $app) {
			
			$app->isUser;
			

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
		$route->respond("/layouts/save", function ($request, $response, $service, $app) {
			
			$app->isUser;
			
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

		//Pages
		$route->respond("/pages", function ($request, $response, $service, $app) {

			$app->isUser;

		    $render = new render();
		    $config = new config();
		    $db = new db("pages");
		    $layouts = new db("layouts");
		    $all = $db->convert($db->all(), 2);
		    $layouts = $layouts->convert($layouts->all());

		    if(isset($request->delete)) {
		    	$db->delete($request->delete);
		    }

		    $render->render("admin.pages", ["system" => $config->system, "pages" => $all, "layouts" => $layouts]);

		});

		//Pages: Create
		$route->respond("/pages/create", function ($request, $response, $service, $app) {
			
			$app->isUser;
			

		    $render = new render();
		    $config = new config();
		    $db = new db("components");
		    $all = $db->sortForm($db->convert($db->all()));
		    $layout = "";

		   	if(isset($request->layout)) {
		   		$layouts = new db("layouts");
		   		$layout = $layouts->get($request->layout);
		   	} 

		   	if(isset($request->edit)) {
		   		$layouts = new db("components");
		   		$edit = new db("pages");
		   		print_r($edit->all());
		   		echo "<br/><br/><br/>";
		   		print_r($layouts->get('Testy'));
		   	}

		    $json = json_encode($all);

		    $render->render("admin.pages.create", ["system" => $config->system, "layout" => $layout, "components" => $all, "json" => $json]);

		});

		//Pages: Save
		$route->respond("/pages/save", function ($request, $response, $service, $app) {
			
			$app->isUser;
			
			$config = new config();

			$post = (object)$_POST;


			if(!$post->page_title) {
				$response->redirect("create?error=1");
			} else {
				$page = new page();
				$publish = $page->publish($_POST);

				if($publish) {
					$response->redirect("create?success=".$post->page_title);
				}
			}
		});

		//Components
		$route->respond("/components", function ($request, $response, $service, $app) {
			
			$app->isUser;
			

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
		$route->respond("POST", "/components/create", function ($request, $response, $service, $app) {
				
			$app->isUser;
			

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
		$route->respond("/variables", function ($request, $response, $service, $app) {
			
			$app->isUser;
			

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
		$route->respond("/extensions", function ($request, $response, $service, $app) {

			$app->isUser;
			

		    $render = new render();
		    $config = new config();
		    $db = new db("extensions");

		    if(isset($request->title) && isset($request->filename) && strpos($request->filename, '.php') !== FALSE) {
		    	$db->set($request->title, $request->filename);
		    }

		    if(isset($_GET['delete'])) {
		    	$db->delete($_GET['delete']);
		    }

		    $render->render("admin.extensions", ["system" => $config->system, "extensions" => $db->all()]);

		});
	});


	$route->dispatch();

 ?>