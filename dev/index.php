<?php require("config.php"); ?>
<?php session_start(); ?>
<?php include("header.php"); ?>
<?php
	if(isset($_GET['id_question'])) :
		include('question-detail.php');
	elseif(isset($_GET['id_user'])) :
		include('accueil.php');
	else :
		include('accueil.php');
	endif;
?>
<?php include("footer.php"); ?>