<?php 

	
	//Namespace configurado dentro do composer.json; 
	
	namespace Ytech;

	// Incorporando namespace do RainTpl ;

	use Rain\Tpl;

// Classe do Projeto 
	class Page{

		// Atributo privado , para dar acesso aos metodos somente dessa classe;
		private $tpl;
		private $options = [];
		private $defaults = [
			"data" => []
		];

		public function __construct($opts = array())
		{

			$this->options = array_merge($this->defaults,$opts);

			// config , $_SERVER["DOCUMENT_ROOT"] traz o diretorio raiz onde o projeto esta instalado.
			$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
			);

		Tpl::configure( $config );

		$this->tpl = new Tpl;

		$this->setData($this->options["data"]);

		$this->tpl->draw("header");

		}

		// Pegar os dados chave e valor da variavel data passada no template;

		private function setData($data = []){
				// Pega variavel e valor
			foreach ($this->options["data"] as $key => $value) {
				$this->tpl->assign($key,$value);
			}			
		}

		// Entrega dados ao Tpl na variavel data
		public function setTpl($name, $data = array(),$returnHTML = false)
		{
				$this->setData($data);

				return	$this->tpl->draw($name,$returnHTML);

		}


		//Metodo Destrutor ultimo a ser executado;

		public function __destruct()
		{
			$this->tpl->draw("footer");
		}

	}

 ?>