<?php 

// INITIALISATION
	error_reporting(E_ALL);
	ini_set("display_error", 1);
	define("RACINE","http://localhost:8888/forum/"); // definit la racine du site

// infos de connexion à la DB
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "root");
	define("DB_NAME", "forum");

// ÉTABLI la connexion avec le serveur en utilistant l'objet PHP 'mysqli' (mysqli library) avec les param de connexion
	$connect = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	//myPrint_r($connect);

// test s'il y a un erreur (propriété errno de l'objet mysqli)
	if($connect->connect_errno) :
		echo "Échec de la connexion : ".$connect->connect_error;	// si c'est le cas écrit echec de la connextion + nom de l'erreur
		exit;																											// bloque tout le script
		else : $connect->set_charset("utf8");											// méthode de $connect qui dit que tous les échanges entre php et db seront en utf8
	endif;

// importe les fonctions
	require_once("functions.php");
?>