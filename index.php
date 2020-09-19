<?php 
	
	session_start();

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Ytech\Page;
	use \Ytech\PageAdmin;
	use \Ytech\Model\User;

	$app = new Slim();

	$app->config('debug',true);

	$app->get('/',function(){

		$page = new Page();

		$page->setTpl("index"); 

	});

		$app->get('/admin',function(){

			// Verificar se ta logado e é Admin
		User::verifyLogin();


		$page = new PageAdmin();

		$page->setTpl("index");

	});

		$app->get('/admin/login',function(){

		$page = new PageAdmin([
			"header" => false,
			"footer" => false
		]);
		$page->setTpl("login");

	});

	$app->post('/admin/login',function(){
		User::login($_POST["login"],$_POST["password"]);

		header("Location: /admin");
		exit;
	});

	$app->get('/admin/logout',function(){
		User::logout();

		header("Location: /admin/login");
		exit;
	});

	$app->run();

 ?>