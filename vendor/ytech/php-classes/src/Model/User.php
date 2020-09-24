<?php 

	namespace Ytech\Model;

	/**
	 * 
	 */

	use \Ytech\DB\Sql;
	use \Ytech\Model;

	class User extends Model
	{
		const SESSION = "User";

		public static function login($login, $password)
		{
			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN",array(
				":LOGIN"=>$login
			));

			if(count($results) === 0){
				throw new \Exception("Usuario inexistente ou senha inválida.");
				
			}

			// Recebe todos os campos da consulta
			$data = $results[0];

			if (password_verify($password,$data["despassword"]) === true) {
				
				$user = new User();

				$user->setData($data);

				// Criando uma sessao para definir login 

				$_SESSION[User::SESSION] = $user->getValues();

				return $user;

			}else{

				throw new \Exception("Usuario inexistente ou senha inválida.");
				
			}

		}

	public static function verifyLogin($inadmin = true){

		// verificar se Session foi definida

		if (
			!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION]
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
		) {
			header("Location: /admin/login");
		}

	}


	public static function logout(){

		$_SESSION[User::SESSION] = null;
	}

	public static function listAll(){
		$sql = new Sql();
	// retorna pra rota;
		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY  b.desperson");

	}

	}
 ?>