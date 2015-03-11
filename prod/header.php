<!doctype html>
<html lang="fr">
<head>
	<link href='http://fonts.googleapis.com/css?family=Merriweather+Sans:700,300italic,400italic,300,400' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/main.css">
	<meta charset="utf-8">
	<title>Exercice Forum - Question</title>
</head>
<body>
<header>

	<h1><a href="index.php">FORUM</a></h1>
	<?php

		if(isset($_GET['error']) AND $_GET['error'] = 'log') :
			echo '<p class ="error--log">wrong login or pw</p>';
		endif;
echo '<div class="login-form">';
// HTML conditionnel : n'affiche le form que s'il y a une session
		if(isset($_SESSION['auteur'])) :
			echo 
				'<p>Bienvenue '.$_SESSION['auteur']->prenom.' '.$_SESSION['auteur']->nom.'</p>
				<a href="log.php?logout=yes">se déconnecter</a>';
		else :
			echo
			'<form action="log.php" method="post">
					<label for="login">
						<span>Votre mail :</span>
						<input type="email" id="login" name="login">
					</label>
					<label for="password">
						<span>Votre mot de passe :</span>
						<input type="password" id="password" name="password">
					</label>
					<input type="submit" value="connexion">
				</form>';
	endif; // fin du HTML conditionnel 
	echo'</div>';
?>


</header>
<main>