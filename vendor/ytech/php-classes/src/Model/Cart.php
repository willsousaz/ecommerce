<?php 

	namespace Ytech\Model;

	/**
	 * 
	 */

	use \Ytech\DB\Sql;
	use \Ytech\Model;
	use \Ytech\Mailer;
	use \Ytech\Model\Product;
	use \Ytech\Model\User;

	class Cart extends Model
	{

		const SESSION = "Cart";

		public static function getFromSession(){

			$cart = new Cart();

			if (isset($_SESSION[Cart::SESSION]) && (int)$_SESSION[Cart::SESSION]['idcart']>0){

				$cart->get((int)$_SESSION[Cart::SESSION]['idcart']);
			}else{


				$cart->getFromSessionId();

				if (!(int)$cart->getidcart() > 0) {
					$data = [
						'dessessionid'=>session_id()
					];

					if(User::checkLogin(false)){


						$user = User::getFromSession();

						$data['iduser'] = $user->getiduser();
					}
				$cart->setData($data);

				$cart->save();

				$cart->setToSession();


				}

			}

			return $cart;
		}

		public function setToSession(){

			$_SESSION[Cart::SESSION] = $this->getValues();
		}

		public function getFromSessionId()
		{
			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_carts WHERE dessessionid  = :dessessionid",[
				':dessessionid'=>session_id()
			]);

			$resultId = count($results);

			if ( $results > 0) {
				$this->setData($results);
			}


		}

		public function get (int $idcart){

			$sql = new Sql();

			$results = $sql->select("SELECT * FROM tb_carts WHERE idcart  = :idcart",[
				':idcart'=>$idcart
			]);

			if (count($results > 0)) {
				$this->setData($results[0]);
			}

			
		}

		public function save()
		{
			$sql = new Sql();

			$results = $sql->select("CALL sp_carts_save(:idcart , :dessessionid, :iduser , :deszipcode, :vlfreight, :nrday)",[
			':idcart'=>$this->getidcart(),
			':dessessionid'=>$this->getdessessionid(),
			':iduser'=>$this->getidusert(),
			':deszipcode'=>$this->getdeszipcode(),
			':vlfreight'=>$this->getvlfreightt(),
			':nrday'=>$this->getnrday()
			]);

			$this->setData($results[0]);

		}


	}
 ?>