<?php require_once("../config.php"); ?>
<?php session_start(); ?>
<?php include("header.php"); ?>
<?php
	if (isset($_SESSION['admin'])):
		include("accueil.php");
	else :
		include("log.php");
	endif;
?>
<?php include("../footer.php"); ?>