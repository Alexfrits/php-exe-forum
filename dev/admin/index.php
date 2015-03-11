<?php require_once("../config.php"); ?>
<?php session_start(); ?>
<?php include("header.php"); ?>
<?php
	// qd on clique sur editer une question
	if(isset($_GET['editQ'])):
		include("question.php");
	elseif(isset($_GET['reponses'])):
		include("reponses.php");
	elseif(isset($_GET['editR'])):
		include("reponses.php");

		//moteur par déf qd on est loggé
	elseif (isset($_SESSION['admin'])):
			include("accueil.php");
		//moteur par déf qd on arrive sur admin
	else :
		include("log.php");
	endif;
?>
<?php include("../footer.php"); ?>