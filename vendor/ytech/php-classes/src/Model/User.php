<?php 

	namespace Ytech\Model;

	/**
	 * 
	 */

	use \Ytech\DB\Sql;
	use \Ytech\Model;
	use \Ytech\Mailer;

	class User extends Model
	{
		const SESSION = "User";

		const SECRET = "Ytech_Ecommerce_";

		const SECRET_IV = "Ytech_Ecommerce_IV_";

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

	public function save()
	{
		
		$sql = new Sql();

		$results[0] = $sql->select("CALL sp_users_save(:desperson,:deslogin,:despassword,:desemail,:nrphone, :inadmin)", array(
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);

	}

	public function get($iduser){

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser",array(
			":iduser" => $iduser
		));

		$this->setData($results[0]);
	}

	public function update(){


		$sql = new Sql();

		$results[0] = $sql->select("CALL sp_usersupdate_save(:iduser,:desperson,:deslogin,:despassword,:desemail,:nrphone, :inadmin)", array(
			":iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]);

	}

	public function delete(){

		$sql = new Sql();

		$sql->select("CALL sp_users_delete(:iduser)", array(
			":iduser"=>$this->getiduser()
		));
	}

	public static function getForgot($email, $inadmin = true){

		$sql = new Sql();

		$results = $sql->select("
			SELECT * FROM
			tb_persons a
			INNER JOIN 
			tb_users b USING(idperson)
			WHERE a.desemail = :email
			",array(
				":email"=>$email
			));
		// Verificando se achou email.

		if (count($results) === 0){
			
			throw new \Exception("Não foi possível recuperar a senha");
			
		}else // criará novo registro
		{
			$data = $results[0];

			$results2 =	$sql->select("CALL sp_userspasswordsrecoveries_create(:iduser,:desip)",array(
				":iduser"=>$data['iduser'],
				":desip"=>$_SERVER["REMOTE_ADDR"]
			));

			if (count($results2[0]) === 0) {

					throw new \Exception("Não foi possível recuperar a senha");

			}else{

				$dataRecovery = $results2[0];

				// Encriptar dados
				$code = openssl_encrypt($dataRecovery['idrecovery'], 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

				$code = base64_encode($code);

				if ($inadmin === true) {
					
					$link = "http://www.ytechecommerce.com.br/admin/forgot/reset?code=$code";
				}else{
					$link = "http://www.hcodecommerce.com.br/forgot/reset?code=$code";
				}

				$link = "http://www.ytechecommerce.com.br/admin/forgot/reset?code=$code";

				$mailer = new Mailer($data["desemail"],$data["desperson"],"Redefinir Senha","forgot",array(
					"name"=>$data["desperson"],
					"link"=>$link				
				));

				$mailer->send();

				return $data;

			}
		}

	}

	public static function validForgotDecrypt($code){

		$code = base64_decode($code);

		$idrecovery = openssl_decrypt($code, 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));

		$sql = new Sql();

		$results = $sql->select("
			select *
			FROM tb_userspasswordsrecoveries a
			INNER JOIN tb_users b USING(iduser)
			INNER JOIN tb_persons c USING(idperson)
			WHERE
				a.idrecovery = :idrecovery
				AND
				a.dtrecovery IS NULL
				AND
				DATE_ADD(a.dtregister, INTERVAL 1 HOUR) >= NOW();
		", array(
			":idrecovery"=>$idrecovery
		));

		if (count($results) === 0)
		{
			throw new \Exception("Não foi possível recuperar a senha.");
		}
		else
		{

			return $results[0];

		}

	}

		public static function setFogotUsed($idrecovery)
	{

		$sql = new Sql();

		$sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
			":idrecovery"=>$idrecovery
		));

	}

	}
 ?>