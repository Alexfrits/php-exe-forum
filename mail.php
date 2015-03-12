<?php 

// ceci est un fichier d'exemple, il ne sert pas au site

	$message = "<h1>bonjour</h1>";
	$message .= "<p>notre newsletter du ".date("d/m/Y")."</p>";

	$destinataire = "frits.alex@gmail.com";

	$headers = "From: info@test.be \r\n";
	$headers .= "MIME-Version: 1.0 \r\n";
	$headers .= "Content-type: text/html; charset=utf-8 \r\n";

	$subject = "Test email en HTML";

	if(mail($destinataire, $subject, $message, $headers)) : echo 'ok'; endif;
?>
