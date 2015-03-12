<?php
	require_once('config.php');
// controller qd on post une question avec le form
	if(isset($_POST['newQuestion'])):
		$sql = sprintf("INSERT INTO question SET question=%s, id_auteur=%s, date='%s', objet=%s, online='y'",
					getSQLValueString($_POST['question'], "text"),
					getSQLValueString($_POST['id_auteur'], "int"),
					date("Y-m-d"),
					getSQLValueString($_POST['objet'], "text")
					);
		// sprintf fct mysql qui permet de formatter la requête -> %s var locale à la fct sprintf qui formatte en texte
		$connect->query($sql);
		echo $connect->error;
		$last_id = $connect->insert_id;

		// upload de l'image
		if (isset($_FILES['image']['error']) AND $_FILES['image']['error'] == 0):
			$myImg = $_FILES['image'];
			$type = $myImg['type'];

			if(array_key_exists($type, $formatsFiles)) :
				$extension = $formatsFiles[$type];
				$folder = "upload/";
				$filename = $last_id;


				$destination = $folder.$filename.$extension;
				$file_uploaded = $myImg['tmp_name'];
				move_uploaded_file($file_uploaded, $destination);
			else : 
				echo "erreur de format";
			endif;
		endif;

		// neutralise le post avec une location
		header("location:index.php");
		// neutralise le script le temps de la redirection, sinon script continue
		exit;
	endif;

// controller qd on clique sur un membre : affiche uniqut ses questions
	if(isset($_GET['id_user'])) :
		$sql = "SELECT idquestion, DATE_FORMAT(date, '%d %m %Y') AS date_fr, id_auteur, objet, idauteurs, nom, prenom, question.online 
						FROM question JOIN auteurs
						ON question.id_auteur = auteurs.idauteurs
						WHERE question.online = 'y' AND id_auteur = '".$_GET['id_user']."' ORDER BY date DESC";

	else :
		$sql = "SELECT idquestion, DATE_FORMAT(date, '%d %m %Y') AS date_fr, id_auteur, objet, idauteurs, nom, prenom, question.online 
						FROM question JOIN auteurs
						ON question.id_auteur = auteurs.idauteurs
						WHERE question.online = 'y' ORDER BY date DESC";
		//echo $sql;	// echo requête pour debug --> comparer avec le msg d'erreur
	endif;

	 	$col = $connect->query($sql);		// col contient l'identifiant de la requête. L'objet $connect est dispo car config.php est chargée avant
			echo $connect->error;					// affiche l'erreur s'il y en a une
		// myPrint_r($col); // pour débug
	?>
	<h1>Liste des questions</h1>
	<ul class="questlist">
	<?php
		if($col->num_rows > 0):
			while ($row = $col->fetch_object()):
				echo '<li class="question">
							<h2 class="question__title"><a class="question__more" href="?id_question='.$row->idquestion.'">'.$row->objet.'</a></h2>
							<p class="question__auth">by : <a href="index.php?id_user='.$row->id_auteur.'"><strong>'.$row->prenom.' '.$row->nom.'</strong></a></p>
							<p class="question__date">'.$row->date_fr.'</p>
							</li>';
			endwhile;
		endif;
	?>
	</ul>
<?php // HTML conditionnel : n'affiche le form que s'il y a une session
	if(isset($_SESSION['auteur'])) : ?>
<form action="accueil.php" method="post" enctype="multipart/form-data" class="form--post">
				<label for="objet">
					<span>Objet:</span>
					<input type="text" name="objet" id="objet" required>
				</label>
				<label for="question"><span>votre question</span>
					<textarea id="question" name="question" required></textarea>
				</label>
				<label for="image"><span>Insérez une image </span>
					<input type="file" id="image" name="image">
				</label>
				<input type="hidden" name="id_auteur" value="<?php echo $_SESSION['auteur']->idauteurs ?>">
				<label for="image">
				<input type="submit" value="répondre" name="newQuestion">
			</form>

<?php endif; // fin du HTML conditionnel ?>