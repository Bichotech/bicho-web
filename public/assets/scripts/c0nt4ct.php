<?php
if ($_POST) {
	$to_Email   	= "fidel.hdz@me.com"; //Replace with recipient email address
	$subject        = 'Message from website '.$_SERVER['SERVER_NAME']; //Subject line for emails
	
	//check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	
		//exit script outputting json data
		$output = json_encode(
		array(
			'type'=>'error', 
			'text' => 'Error en servidor, favor de intentar más tarde.'
		));
		
		die($output);
    } 
	
	//check $_POST vars are set, exit if any missing
	if(!isset($_POST["nombre"]) || !isset($_POST["correo"]) || !isset($_POST["mensaje"]))
	{
		$output = json_encode(array('type'=>'error', 'text' => 'No se permiten campos vacíos'));
		die($output);
	}

	//Sanitize input data using PHP filter_var().
	$user_Name        = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
	$user_Email       = filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL);
	$user_Message     = filter_var($_POST["mensaje"], FILTER_SANITIZE_STRING);
	$user_biz		  = filter_var($_POST["negocio"], FILTER_SANITIZE_STRING);
	$user_services	  = filter_var($_POST["servicios"], FILTER_SANITIZE_STRING);
	
	$user_Message = str_replace("\&#39;", "'", $user_Message);
	$user_Message = str_replace("&#39;", "'", $user_Message);
	
	//additional php validation
	if(strlen($user_Name)<4) // If length is less than 4 it will throw an HTTP error.
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Nombre muy corto o está vacío'));
		die($output);
	}
	if(!filter_var($user_Email, FILTER_VALIDATE_EMAIL)) //email validation
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Utilice un email válido'));
		die($output);
	}
	if(strlen($user_Message)<5) //check emtpy message
	{
		$output = json_encode(array('type'=>'error', 'text' => 'Mensaje muy corto.'));
		die($output);
	}

	$body = $user_Message . "\r\n\n"  .'-- '.$user_Name. "\r\n" .'-- '.$user_Email. "\r\n" .'-- '.$user_biz. "\r\n" .'-- '.$user_services;
	
	//proceed with PHP email.
	$headers = 'From: '.$user_Email.'' . "\r\n" .
	'Reply-To: no-reply@bicho.tech' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
	$sentMail = @mail($to_Email, $subject, $body, $headers);
	
	if(!$sentMail)
	{
		$output = json_encode(array('type'=>'error', 'text' => 'No se pudo enviar la informacíon, favor de intentar más tarde.'));
		die($output);
	}else{
		$output = json_encode(array('type'=>'message', 'text' => $user_Name .', gracias por tu mensaje'));
		die($output);
	}
}
?>