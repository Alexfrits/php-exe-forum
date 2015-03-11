<?php
  require_once('config.php');
// controller qd on post une question avec le form
  if(isset($_POST['newReponse'])):
    $sql = sprintf(
            "INSERT INTO reponses SET id_question = '%s', reponse='%s', id_auteur='%s', online='y', date='%s'",
            $_POST['id_question'], // récupère la valeur de id_question dans le formulaire grâce au champ masqué
            $_POST['reponse'],
            $_POST['id_auteur'],
            date("Y-m-d")
          );
  // sprintf fct mysql qui permet de formatter la requête -> %s var locale à la fct sprintf qui formatte en texte
  $connect->query($sql);
  // neutralise le post avec une location
  header("location:index.php?id_question=".$_POST['id_question']);
  // neutralise le script le temps de la redirection, sinon script continue
  exit;
  endif;
  // sql pour la question
  $sql = "SELECT idquestion, question, DATE_FORMAT(date, '%d %m %Y') AS date_fr, id_auteur, objet, idauteurs, nom, prenom, question.online 
          FROM question JOIN auteurs
          ON question.id_auteur = auteurs.idauteurs
          WHERE question.online = 'y' AND idquestion = ".$_GET['id_question']." ORDER BY date DESC";

  $detailQ = $connect->query($sql);
  echo $connect->error;

  // sql pour les réponses
  $sql = "SELECT idreponses, id_question, reponse, reponses.id_auteur, reponses.online, DATE_FORMAT(reponses.date, '%d %m %Y') AS date_fr, auteurs.nom, auteurs.prenom, auteurs.online
          FROM reponses JOIN auteurs
          ON reponses.id_auteur = auteurs.idauteurs
          WHERE auteurs.online = 'y' AND reponses.id_question = '".$_GET['id_question']."' ORDER BY reponses.date ASC";

  $detailR = $connect->query($sql);
  echo $connect->error;    
  unset($sql);   

  // echo '<pre class="php-error">'.$sql1."</pre>";

  // myPrint_r($detailQ);
  // myPrint_r($detailR);
  if ($detailQ->num_rows > 0):
    while($question = $detailQ->fetch_object()):
      echo '<div class="question">
              <h2 class="question__object">'.$question->objet.'</h2>
              <ul class="question__infos">
                <li class="question__infos__firstname">'.$question->prenom.' '.$question->nom.'</li>
                <li class="question__infos__date">'.$question->date_fr.'</li>
              </ul>
              <p class ="question__text">'.$question->question.'</p>
            </div>';
    endwhile;
  endif;

  if ($detailR->num_rows > 0):
    while($reponses = $detailR->fetch_object()):
      echo '<ul class="list-answers">
              <li class="answer">
                  <ul class="answer__infos">
                    <li><a href="" class="answer__infos__name"><strong>'.$reponses->prenom.' '.$reponses->nom.'</strong></a></li>
                    <li class="answer__infos__date">'.$reponses->date_fr.'</li>
                  </ul>
                  <p class ="answer__text">'.$reponses->reponse.'</p>
              </li>
            </ul>';
    endwhile;
  endif;

 // HTML conditionnel : n'affiche le form que s'il y a une session
  if(isset($_SESSION['auteur'])) :
    echo
      '<form action="question-detail.php" method="post">
        <label for="reponse">
          <span>Votre réponse :</span>
          <textarea id="reponse" name="reponse"></textarea>
        </label>
        <input type="hidden" name="id_auteur" value="'.$_SESSION['auteur']->idauteurs.'">
        <input type="hidden" name="id_question" value="'.$_GET['id_question'].'">
        <input type="submit" value="répondre" name="newReponse">
      </form>';
endif; // fin du HTML conditionnel ?>