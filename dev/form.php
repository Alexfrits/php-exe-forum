<?php require("config.php");
//ceci est un fichier test et n'a aucune incidence sur le site
//-----------------------------------------------------------------
?>


<form action="form.php" method="post" enctype="multipart/form-data" >
	<input type="text" name="exemple">
	<input type="file" name="image">
	<input type="submit" value="Go">
</form>
<?php
// vérif que le fichier existe et qu'il n'y a pas d'erreurs
	if(isset($_FILES['image']['error']) AND $_FILES['image']['error'] == 0):

		$myImg = $_FILES['image'];
		$type = $myImg['type'];

		// vérifie si le type de l'img existe en tant que clé dans l'array $formatsFiles (fichier config)
		if(array_key_exists($type, $formatsFiles)) :
			// $filename = date("YmdHis"); // méthode simple de définition du nom avec la date - heure - seconde

			// définit l'extension via son index dans l'array $formatsFiles
			$extension = $formatsFiles[$type];
			$folder = "upload/";
			// découpe le nom du fichier avec "." comme séparateur
			$filename = explode(".", $myImg['name']);
			// redéfinit $filename avec la première partie (celle devant l'extension)
			$filename = $filename[0];
			// test si le fichier existe ()
			while(file_exists($folder.$filename.$extension)):
			// tant que le fichier existe, on rajoute 1 à son nom de fichier
				$filename = $filename."1";
			endwhile;

			// une fois qu'il n'y a plus de fichiers qui portent le même nom, écrit le fichier
			$destination = $folder.$filename.$extension;
			// $destination = "upload/".$filename.$extension;
			$file_uploaded = $myImg['tmp_name'];
			move_uploaded_file($file_uploaded, $destination);
			
		else : echo "erreur de format";
		endif;

		echo $myImg['name'];
	endif;

	myPrint_r($_POST);
	myPrint_r($_GET);
	myPrint_r($_FILES);
?>

<?php

/*
Form doit absolument être en POST pour recupérer le fichier
stocké dans dossier temporaire jusqu'à la fin du script puis disparait

      [image] => Array
        (
            [name] => Capture d’écran 2015-01-14 à 10.09.25.png
            [type] => image/png
            [tmp_name] => /Applications/MAMP/tmp/php/phpAoRiuZ
            [error] => 0
            [size] => 13893
          )

IL FAUT LIMITER les extensions aux types de fichiers "sécurisés" (pour ne pas recevoir des scripts php etc)

*/

?>