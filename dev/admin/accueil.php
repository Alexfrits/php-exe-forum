<?php 

if(isset($_SESSION['admin'])):


		$sql = "SELECT idquestion, question, date, online, id_auteur, objet
						FROM question
						ORDER BY date DESC";

		$result = $connect->query($sql);
		echo $connect->error;

echo '<div class="login-form">';

	echo '<a href="log.php?logout=yes">se déconnecter</a>
				</div>
				</header>
				<main>
				<p>votre niveau de droits: '.$_SESSION['admin']->niveau.'</p>';

				if($result->num_rows > 0):
					echo'<table class="admin-tableau">';

					while($row = $result->fetch_object()):
						echo '<tr>
									<td>'.$row->objet.'</td>
									<td><a href="question.php?edit='.$row->idquestion.'">Éditer</a></td>
									<td><a href="question.php?delete='.$row->idquestion.'">Supprimer</a></td>
									</tr>';

					endwhile;	

					echo '</table>';
				endif;
endif;
?>