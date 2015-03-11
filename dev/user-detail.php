<?php
	global $connect;
	$sql = "SELECT idquestion, DATE_FORMAT(date, '%d %m %Y') AS date_fr, id_auteur, objet, idauteurs, nom, prenom, question.online 
					FROM question JOIN auteurs
					ON question.id_auteur = auteurs.idauteurs
					WHERE auteurs.online = 'y' AND idauteurs = ".$_GET['id_user']."
					ORDER BY date DESC";
	//echo $sql;	// echo requête pour debug --> comparer avec le msg d'erreur

 	$user = $connect->query($sql);
		echo $connect->error;
	myPrint_r($user);
?>