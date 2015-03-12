<?php
  require_once('config.php');
// controller qd on post une question avec le form
  // insert dans la DB
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
    $last_id = $connect->insert_id; // récup l'id de l'objet qu'on vient d'insérer à la DB

    // neutralise le script avec une location

    // envoi du mail à l'auteur de la question
    $sql = sprintf(
          "SELECT idquestion, question, date, question.online, id_auteur, objet, nom, prenom, email
          FROM question JOIN auteurs
          ON question.id_auteur = auteurs.idauteurs
          WHERE idquestion = %s",
          $_POST['id_question']
        );

    $result = $connect->query($sql);
    echo $connect->error;

    $row =  $result->fetch_object();

    // CONTENU DU MAIL
    // lien de la réponse en absolu
    $lienReponse = RACINE.'?id_question='.$_POST['id_question'];
    //titre du mail
    $objet = 'reponse à '.$row->objet;
    // corps de texte
    $texte = '<h1>Une réponse à été postée à votre question sur le meilleur forum du monde</h1>';
    $texte .='retrouvez-la <a href="'.$lienReponse.'">sur le forum</a>';
    //email de celui qui a posé la question
    $destinataire = $row->email;
    // header
    $headers = "From: info@test.be \r\n";
    $headers .= "MIME-Version: 1.0 \r\n";
    $headers .= "Content-type: text/html; charset=utf-8 \r\n";

    // si le mail ne s'envoie pas
    if(!mail($destinataire, $objet, $texte, $headers)) :
      echo 'Problème à l’envoi';
      // s'il s'envoie, on fait la redirection
      exit;
    else :
      header("location:index.php?id_question=".$_POST['id_question']);
      // neutralise le script le temps de la redirection, sinon script continue
      exit;
    endif;

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

  if ($detailQ->num_rows > 0):
    while($question = $detailQ->fetch_object()):

      $img = "upload/".$question->idquestion.".jpg";

      if(file_exists($img)) :
        $question__img = '<img src="'.$img.'" class="question__infos__img">';
      endif;

      echo '<div class="question">
              <h2 class="question__object">'.$question->objet.'</h2>
              <ul class="question__infos">
                <li class="question__infos__firstname">'.$question->prenom.' '.$question->nom.'</li>
                <li class="question__infos__date">'.$question->date_fr.'</li>
                <li>'.$question__img.'</li>
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

  // FORMULAIRE DE RÉPONSE
  // ----------------------------
  // HTML conditionnel : n'affiche le form que s'il y a une session
  if(isset($_SESSION['auteur'])) :
    echo
      '<form action="question-detail.php" method="post" class="form--post">
        <label for="reponse">
          <span>Votre réponse :</span>
          <textarea id="reponse" name="reponse" required></textarea>
        </label>
        <input type="hidden" name="id_auteur" value="'.$_SESSION['auteur']->idauteurs.'">
        <input type="hidden" name="id_question" value="'.$_GET['id_question'].'">
        <input type="submit" value="répondre" name="newReponse">
      </form>';
endif; // fin du HTML conditionnel ?>