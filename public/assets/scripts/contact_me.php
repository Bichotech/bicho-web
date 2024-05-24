<?php
if ($_POST) {
	$to_Email   	= "fh@bicho.tech"; //Replace with recipient email address
	$subject        = 'Mensaje enviado desde '.$_SERVER['SERVER_NAME']; //Subject line for emails
	
	//Sanitize input data using PHP filter_var().
	$user_Email       = filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL);
	$user_Message     = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);
	$user_Name        = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
	$user_biz		  = filter_var($_POST["negocio"], FILTER_SANITIZE_STRING);
	$user_services	  = filter_var($_POST["servicios"], FILTER_SANITIZE_STRING);
	
	$user_Message = str_replace("\&#39;", "'", $user_Message);
	$user_Message = str_replace("&#39;", "'", $user_Message);

	$body = $user_Message . "\r\n\n"  .'-- '.$user_Name. "\r\n" .'-- '.$user_Email. "\r\n" .'-- '.$user_biz. "\r\n" .'-- '.$user_services;
	
	//proceed with PHP email.
	$headers = 'From: '.$user_Email.'' . "\r\n" .
	'Reply-To: no-reply@bicho.tech' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
	$sentMail = mail($to_Email, $subject, $body, $headers);

	echo $sentMail;
	
	if(!$sentMail) {
		echo 'No se pudo enviar la informacíon, favor de intentar más tarde.';
	} else {
		echo 'Si ser envió.' . $body;
	}
}
?>