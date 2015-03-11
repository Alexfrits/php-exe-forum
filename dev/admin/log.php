<?php 
require_once("../config.php");

// logout a été cliqué dans accueil.php
if (isset($_GET['logout']) AND $_GET['logout'] == 'yes') :
	session_start();
	unset($_SESSION['admin']); // va enlever l'entrée auteur dans la session
	header("location:index.php"); exit;
endif;

// mauvais mdp ou pw

if(isset($_GET['error']) AND $_GET['error'] == "log"):
	echo "bad ID";

endif;

// on a entré ses ID de connexion
if (isset($_POST['login'])) :
	$sql = sprintf(
		"SELECT log, pass, niveau
		FROM admin
		WHERE log = '%s' AND pass = '%s'
		",
		$_POST['login'],
		$_POST['password']
		);
	$result = $connect ->query($sql);
		echo $connect->error;

		if($result->num_rows > 0) :
			session_start();
			while ($row = $result->fetch_object()):
				$_SESSION['admin'] = $row;
			header("location:index.php"); exit;
			endwhile;
		else : header("location:index.php?error=log"); exit;
		endif;

endif;
?>


<div class="login-form">
<form action="log.php" method="post">
		<label for="login"><span>login</span>
			<input type="text" id="login" name="login" required>
		</label>
		<label for=""><span>mot de passe</span>
			<input type="password" id="password" name="password" required>
		</label>
		<input type="submit" value="connexion">
	</form>
	</header>
</div>