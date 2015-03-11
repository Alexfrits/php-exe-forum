<?php
require_once('../config.php');

// on a cliqué sur delete d'une question
	if(isset($_GET['delete'])) : 
		$sql = sprintf("DELETE FROM question WHERE idquestion ='%s'",
										$_GET['delete']);

		$connect->query($sql);
	// on supprime aussi ses réponses
		$sql = sprintf("DELETE FROM reponses WHERE idquestion ='%s'",
										$_GET['delete']);

		$connect->query($sql);

		header("location:index.php"); exit;
	endif;

// on a cliqué sur valider les modifs dans le formulaire edit
// ----------------------------------------------------------
// getSQLValueString traite les données en fct des param qu'on lui passe
// il faut retirer les simple quotes dans le sprintf car getSQLVS le fait déjà
// puis sprintf remplace tous les %s par ces valeurs
	if(isset($_POST['editQ'])) : 
		$sql = sprintf(
						"UPDATE question
						SET question = %s, date = %s, online = %s, objet = %s
						WHERE idquestion = %s",
						GetSQLValueString($_POST['question'], "text"),
						GetSQLValueString($_POST['date'], "date"),
						GetSQLValueString($_POST['online'], "text"),
						GetSQLValueString($_POST['objet'], "text"),
						GetSQLValueString($_POST['idquestion'], "int")
					);
		$connect->query($sql);
		echo $connect->error;

		header("location:index.php"); exit;
	endif;

	// on a cliqué sur edit d'une question
	if(isset($_GET['editQ'])) : 
		$sql = sprintf(
						"SELECT *
						FROM question
						WHERE idquestion ='%s'",
						$_GET['editQ']);

		$result = $connect->query($sql);
		echo $connect->error;?>


</header>
		<?php // bouton edit 

		if ($result->num_rows > 0):
  		while($row = $result->fetch_object()):?>

			<form action="question.php" method="post" class="edit-form">
				<input type="hidden" name="idquestion" value="<?php echo $row->idquestion ?>">
				<label for="objet"><span>objet :</span>
					<input type="text" id="objet" name="objet" value="<?php echo $row->objet ?>">
				</label>
				<label for="question"><span>question :</span>
					<textarea id="question" name="question"><?php echo $row->question ?></textarea>
				</label>
				<label for="date"><span>date :</span>
					<input type="date" id="date" name="date" value="<?php echo $row->date ?>">
				</label>
				<fieldset>
				<legend>online :</legend>
					<label for="online-yes">
						<input type="radio" id="online-yes" name="online" value="y" <?php  echo ( $row->online == 'y' ) ? "checked" : "" ;?>>
						<span>yes</span>
					</label>
					<label for="online-no">
						<input type="radio" id="online-no" name="online" value="n" <?php  echo ( $row->online == 'n' ) ? "checked" : "" ;?>>
						<span>no</span>
					</label>
				</fieldset>
				<fieldset>
					<input type="submit" value="Valider les modifs" name="editQ">
					<a href="index.php">annuler</a>
				</fieldset>
			</form>
			
<?php	endwhile;
		endif;
	endif; ?>