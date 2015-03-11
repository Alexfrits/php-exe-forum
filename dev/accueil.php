<?php
	require_once('config.php');
// controller qd on post une question avec le form
	if(isset($_POST['newQuestion'])):
		$sql = sprintf("INSERT INTO question SET question='%s', id_auteur='%s', date='%s', objet='%s', online='y'",
					$_POST['question'],
					$_POST['id_auteur'],
					date("Y-m-d"),
					$_POST['objet']
					);
		// sprintf fct mysql qui permet de formatter la requête -> %s var locale à la fct sprintf qui formatte en texte
	$connect->query($sql);
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
			echo $connect->error;						// affiche l'erreur s'il y en a une
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
	if(isset($_SESSION['auteur'])) :
		myPrint_r($_SESSION);
		echo
			'<form action="accueil.php" method="post">
				<label for="question"></label>
				<textarea id="question" name="question"></textarea>
				<label for="objet">Objet:</label>
				<input type="text" name="objet" id="objet">
				<input type="hidden" name="id_auteur" value="'.$_SESSION['auteur']->idauteurs.'">
				<input type="submit" value="répondre" name="newQuestion">
			</form>';

endif; // fin du HTML conditionnel ?>