<?php
require_once('../config.php');


// DELETE
//===========================================================================
// on a cliqué sur delete d'une reponse
	if(isset($_GET['delete'])) : 
		$sql = sprintf(
							"DELETE FROM reponses
							WHERE idreponses = %s",
							$_GET['delete']
						);

		$connect->query($sql);
		header("location:".$_SERVER['HTTP_REFERER']); exit;
	endif;

// UPDATE
//===========================================================================

// on a cliqué sur valider les modifs dans le formulaire edit
// ----------------------------------------------------------
// getSQLValueString traite les données en fct des param qu'on lui passe
// il faut retirer les simple quotes dans le sprintf car getSQLVS le fait déjà
// puis sprintf remplace tous les %s par ces valeurs
	if(isset($_POST['editR'])) : 
		$sql = sprintf(
						"UPDATE reponses
						SET reponse = %s, date = %s, online = %s
						WHERE idreponses = %s",
						GetSQLValueString($_POST['reponse'], "text"),
						GetSQLValueString($_POST['date'], "date"),
						GetSQLValueString($_POST['online'], "text"),
						GetSQLValueString($_POST['idreponses'], "int")
					);
		$connect->query($sql);
		echo $connect->error;

		header("location:index.php"); exit;
	endif;



// on a cliqué sur edit d'une reponse
	if(isset($_GET['editR'])) : 
		$sql = sprintf(
						"SELECT *
						FROM reponses
						WHERE idreponses = %s",
						$_GET['editR']
					);

		$reponse = $connect->query($sql);
		echo $connect->error;


		if($reponse->num_rows > 0) :
			while($row = $reponse->fetch_object()):?>
				</header>
					<form action="reponses.php" method="post" class="edit-form">
						<input type="hidden" name="idreponses" value="<?php echo $row->idreponses ?>">
						<label for="reponse"><span>réponse :</span>
							<textarea id="reponse" name="reponse" required><?php echo $row->reponse ?></textarea>
						</label>
						<label for="date"><span>date :</span>
							<input type="date" id="date" name="date" value="<?php echo $row->date ?>">
						</label>
						<fieldset>
						<legend>online :</legend>
							<label for="online-yes">
							<span>yes </span>
								<input type="radio" id="online-yes" name="online" value="y" <?php  echo ( $row->online == 'y' ) ? "checked" : "" ;?>>
							</label>
							<label for="online-no">
							<span>no </span>
								<input type="radio" id="online-no" name="online" value="n" <?php  echo ( $row->online == 'n' ) ? "checked" : "" ;?>>
							</label>
						</fieldset>
						<fieldset>
							<input type="submit" value="valider" name="editR">
							<a href="index.php?reponses=<?php echo $row->id_question ?>">annuler</a>
						</fieldset>
					</form>
				<?php endwhile;
			endif;
		endif;

// AFFICHER
//===========================================================================
	if(isset($_GET['reponses'])) :

	// sélectionner le titre de la question sur laquelle on a cliqué
		$sql = sprintf(
						"SELECT objet
						FROM question
						WHERE idquestion = %s",
						$_GET['reponses']
					);

		$result = $connect->query($sql);
		$question = $result->fetch_object();


		// afficher les réponses correspondantes
		$sql = sprintf(
						"SELECT idreponses, id_question, reponse, id_auteur, reponses.online, date, idauteurs, nom, prenom
						FROM reponses join auteurs
						ON reponses.id_auteur = auteurs.idauteurs
						WHERE id_question = %s
						ORDER BY date ASC",
						$_GET['reponses']
					);
		$result = $connect->query($sql);
		echo $connect->error; ?>
		</header>
		<main>
			<h2><?php echo $question->objet ?></h2>
			<?php if($result->num_rows > 0) : ?>

				<table class="admin-tableau">
					<thead>
						<th>auteur</th>
						<th>date</th>
						<th>réponse</th>
						<th>Modifier</th>
						<th>Effacer</th>
					</thead>

					<?php	while($row = $result->fetch_object()):?>
						<tr>
							<td><?php echo $row->prenom." ".$row->nom ?></td>
							<td><?php echo $row->date ?></td>
							<td><?php echo $row->reponse ?></td>
							<td><a href="index.php?editR=<?php echo $row->idreponses?>">Edit</a></td>
							<td><a href="reponses.php?delete=<?php echo $row->idreponses?>" class="btn--delete" onclick="if(!confirm('Sûr?')) return false;">Delete</a></td>
						</tr>
					<?php endwhile;

					else : echo "pas de réponses";?>

				</table>

<?php	endif; endif;?>