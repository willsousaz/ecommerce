<?php 
	
	session_start();

	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Ytech\Page;
	use \Ytech\PageAdmin;
	use \Ytech\Model\User;

	$app = new Slim();

	$app->config('debug',false);


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

	// Inicio Rotas Administradores;

	$app->get("/admin/users", function(){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

	// Lista todos os usuários;

		$users = User::listAll();

		$page = new PageAdmin();

		$page->setTpl("users",array("users"=>$users));


	});

	// Rota Create


	$app->get("/admin/users/create", function(){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

	// Lista todos os usuários;

		$page = new PageAdmin();

		$page->setTpl("users-create");

	});

	// Rota Deletar

	$app->get("admin/users/:iduser/delete", function($iduser){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

	});

	// Rota Update

	$app->get("/admin/users/:iduser", function($iduser){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

	// Lista todos os usuários;

		$page = new PageAdmin();

		$page->setTpl("users-update");

	});

	$app->post("admin/users/:iduser", function($iduser){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

	});


	//Fim Rotas Administradores;

	$app->run();

 ?>