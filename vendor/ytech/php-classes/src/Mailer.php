<?php 


	namespace Ytech;

	use Rain\Tpl;


	Class Mailer{

		const USERNAME = "yuri.wilson18@gmail.com";
		const PASSWORD = "#isaacayumi1820#";

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

			$this->mail = new \PHPMailer();
 			$this->mail->isSMTP(); // telling the class to use SMTP
 			$this->mail->SMTPDebug = 0;
 			$this->mail->Debugoutput = 'html';
  			$this->mail->Host       = "smtp.gmail.com"; // sets the SMTP server
  			$this->mail->Port = 587;
  			$this->mail->SMTPSecure = 'tls';
  			$this->mail->SMTPAuth   = true;                  // enable SMTP authentication
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