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

		header("location:index.php");
		exit;
	endif;

	// on a cliqué sur edit d'une question
	if(isset($_GET['edit'])) : 
		$sql = sprintf("SELECT idquestion, question, date, online, objet
						FROM question
						WHERE idquestion ='%s'",
						$_GET['edit']);

		$connect->query($sql);
		echo $connect->error;

  		while():
				echo '<form>
			
			';
			endwhile;

		header("location:index.php");
		exit;
	endif;
?>