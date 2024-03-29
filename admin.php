<?php 

use \Ytech\PageAdmin;
use \Ytech\Model\User;


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

	$app->get("/admin/users/:iduser/delete", function($iduser){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

		$user = new User();

		$user->get((int)$iduser);

		$user->delete();

		header("Location: /admin/users");

		exit;
		
	});

	// Rota Update , atualiza os dados

	$app->get("/admin/users/:iduser", function($iduser){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

		$user = new User();

		$user->get((int)$iduser);

		$page = new PageAdmin();

		$page->setTpl("users-update",array(
			"user"=>$user->getValues()
		));


	});

	// Enviando pelo metodo post ao banco;
	$app->post('/admin/users/create', function(){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

		$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, ["cost"=>12
 	]);

		$user->setData($_POST);

		$user->save();

		header("Location: /admin/users");

		exit;


	});

	$app->post("/admin/users/:iduser", function($iduser){

	// Verifica se esta logado e se é Administrador;

		User::verifyLogin();

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

		$user->get((int)$iduser);

		$user->setData($_POST);

		$user->update();

		header("Location: /admin/users");

		exit;

	});

// Rota de recuperação de Email 

	$app->get("/admin/forgot", function(){

		$page = new PageAdmin([
			"header" => false,
			"footer" => false
		]);

		$page->setTpl("forgot");

	});

// Rota para tratar dados com formulario post

	$app->post("/admin/forgot", function(){	

	$user = User::getForgot($_POST["email"]);


	header("Location: /admin/forgot/sent");

	exit;	

	});

	$app->get("/admin/forgot/sent", function(){

		$page = new PageAdmin([
			"header" => false,
			"footer" => false
		]);
		$page->setTpl("forgot-sent");

	});


	$app->get("/admin/forgot/reset", function(){

		$user = User::validForgotDecrypt($_GET["code"]);

		$page = new PageAdmin([
			"header" => false,
			"footer" => false
		]);

		$page->setTpl("forgot-reset", array(
			"name"=>$user["desperson"],
			"code"=>$_GET["code"]
		));


	});

	$app->post("/admin/forgot/reset", function(){


		$forgot = User::validForgotDecrypt($_POST["code"]);


		User::setFogotUsed($forgot["idrecovery"]);

		$user = new User();

		$user->get((int)$forgot["iduser"]);

		$password = password_hash($_POST["password"], PASSWORD_DEFAULT,[
			"cost"=>12
		]);
		$user->setPassword($password);

		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("forgot-reset-success");
	});

 ?>