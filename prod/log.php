<?php 
	require_once('config.php');
if (isset($_GET['logout']) AND $_GET['logout'] == 'yes') :
	session_start();
	// session_destroy();	// trop hardcore, détruit tout
	unset($_SESSION['auteur']); // va enlever l'entrée auteur dans la session
	header("location:index.php"); exit;
endif;

// initialisation de la session
if (isset($_POST['password'])):
	$sql = sprintf(
					"SELECT idauteurs, nom, prenom, email
					FROM auteurs
					WHERE email='%s' AND password='%s'",
					$_POST['login'],
					$_POST['password']
				);
	// on ne prend pas le mdp dans le select

$result = $connect ->query($sql);
	echo $connect->error;

// si les infos de connexion correspondent à une entrée dans la DB, on démarre la session avec 
	if($result->num_rows == 1) :
		session_start();
		while($row = $result->fetch_object()):
			$_SESSION['auteur'] = $row; // mémorise l'objet dans auteur
		header("location:index.php"); exit;	// redirection vers index + exit pour bloquer le script
		endwhile;
	else : header("location:index.php?error=log"); exit;
	endif;

endif;
?>