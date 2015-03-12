<?php
	require_once('config.php');
	// myPrint_r($_POST);

		$sql = sprintf(
							"SELECT log, pass, niveau
							FROM admin
							WHERE log = '%s' AND pass = '%s'
							",
							$_POST['login'],
							$_POST['password']
						);
		$result = $connect->query($sql);
		echo $connect->error;

		if($result->num_rows == 1):
			session_start();
		$myAdmin = $result->fetch_object();
		$_SESSION['admin'] = $myAdmin;
			echo 'bienvenue';
		else : 
			echo 'WROONG!';
		exit;

		endif;
?>