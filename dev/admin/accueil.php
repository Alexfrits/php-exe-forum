<?php 

if(isset($_SESSION['admin'])):

	$sql = "SELECT idquestion, question, date, question.online, id_auteur, objet, idauteurs, nom, prenom
						FROM question join auteurs
						ON question.id_auteur = auteurs.idauteurs
						ORDER BY date DESC";

		$result = $connect->query($sql);
		echo $connect->error;
?>

<p class="rights-level">votre niveau de droits: <?php echo $_SESSION['admin']->niveau; ?></p>

				<?php if($result->num_rows > 0): ?>
					<table class="admin-tableau">
						<thead>
							<th>objet</th>
							<th>auteur</th>
							<th>date</th>
							<th>Éditer</th>
							<th>Réponses</th>
							<th>Effacer</th>
						</thead>
					<?php while($row = $result->fetch_object()): ?>

						<tr>
							<td><?php echo $row->objet ?></td>
							<td><?php echo $row->prenom." ".$row->nom ?></td>
							<td><?php echo $row->objet ?></td>
							<td><a href="index.php?editQ=<?php echo $row->idquestion ?>">Éditer</a></td>
							<td><a href="index.php?reponses=<?php echo $row->idquestion?>">Réponses</a></td>
							<td><a href="question.php?delete=<?php echo $row->idquestion ?>" class="btn--delete" onclick="if(!confirm(\'Sûr?\')) return false;">Supprimer</a></td>
						</tr>

		<?php endwhile;	?>

					</table>
<?php endif; endif; ?>