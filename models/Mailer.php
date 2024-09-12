<?php 
	
	/**
	* @file Mailer.php
	* @brief Declaraciones de la clase Mailer para el envio de correo electrónico
	* @author Matias Leonardo Baez
	* @date 2024
	* @contact elmattprofe@gmail.com
	*/
	
	// se crea la clase Mailer
	class Mailer{

		/*< constructor de la clase*/
		function __construct(){

		}

		/**
		 * 
		 * @brief envia un correo electrónico
		 * @params array $params array asociativo [destinatario, motivo, contenido]
		 * @return 
		 * 
		 * */
		function send($params){

			$destinatario = $params["destinatario"];
			$motivo = $params["motivo"];
			$contenido = $params["contenido"];

			$mail = new PHPMailer\PHPMailer\PHPMailer();

	        $mail->isSMTP();
	        $mail->SMTPDebug = 0 ;
	        $mail->Host = $_ENV["MAILER_HOST"];
	        $mail->Port = $_ENV["MAILER_PORT"];
	        $mail->SMTPAuth = $_ENV["MAILER_SMTP_AUTH"];
	        $mail->SMTPSecure = $_ENV["MAILER_SMTP_SECURE"];
	        $mail->Username = $_ENV["MAILER_REMITENTE"];
	        $mail->Password = $_ENV["MAILER_PASSWORD"];

	        $mail->setFrom($_ENV["MAILER_REMITENTE"], $_ENV["MAILER_NOMBRE"]);
	        $mail->addAddress($destinatario);

	        $mail->isHTML(true);

	        $mail->Subject = utf8_decode($motivo);
	        $mail->Body = utf8_decode($contenido);

	        if(!$mail->send()){
	            error_log("Mailer no se pudo enviar el correo!" );
				$body = array("errno" => 1, "error" => "No se pudo enviar.");
	        }else{
				$body = array("errno" => 0, "error" => "Enviado con exito.");
			}   
	 
			return $body;
		}
	}


 ?>