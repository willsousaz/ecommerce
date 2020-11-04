<?php 


	namespace Ytech;

	use Rain\Tpl;
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;


	Class Mailer{

		const USERNAME = "yuri@ytechsolucoes.com.br";
		const PASSWORD = "#Include2020#";

		private $mail;

		public function __construct($toAddress, $toName, $subject, $tplName, $data = array())
		{

		// config , $_SERVER["DOCUMENT_ROOT"] traz o diretorio raiz onde o projeto esta instalado.
			$config = array(
			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."/views/email/",
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
			);

		Tpl::configure( $config );

		$tpl = new Tpl;

		foreach ($data as $key => $value) {
			$tpl->assign($key, $value);
		}

		$html = $tpl->draw($tplName,true);

		
			$this->mail = new \PHPMailer( );
 			$this->mail->isSMTP(); // telling the class to use SMTP
 			$this->mail->SMTPDebug = 2;
 			$this->mail->Debugoutput = 'html';
  			$this->mail->Host = "mail.ytechsolucoes.com.br"; // sets the SMTP server
  			$this->mail->Port = 465;
  			$this->mail->SMTPAuth   = true;   
  			$this->mail->SMTPSecure = 'ssl';
  			$this->mail->Username   = Mailer::USERNAME; // SMTP account username
  			$this->mail->Password   = Mailer::PASSWORD;        // SMTP account password
  			$this->mail->setFrom(Mailer::USERNAME,'Ytech Soluções');
  			$this->mail->addAddress($toAddress,$toName);
  			$this->mail->Subject = $subject;
  			$this->mail->msgHTML($html);
  			$this->mail->AltBody = 'This is plain-text message body';
		}

		public function send(){
			return $this->mail->send();
		}
	}

 ?>