<?php

$email       = checkInput($_POST['email']);
$controlla   = checkInput($_POST['controlla']);

 if($controlla == "si")
 {
  foreach($_POST as $key=>$value)
  {
    if (preg_match("([<>?+'%&()]),", $value) || preg_match('/"/', $value))
    {
      $erroreCaratteri = "1";
    }
    if ($value == "")
    {
      $erroreVuoto = "1";
    }
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    $erroreMail = "1";
  }
  //funzione che verifica se l'email appartiene a descartes
  if(strpos($email, "etu.parisdescartes.fr")=="")
  {
    $nonDescartes ="1";
  }

  if(strlen($email)!=29)
  {
    $nonDescartes ="1";
  }
  //fine funzione che verifica se l'email appartiene a descartes

}

include 'admin-pannel/database.php';

if ($erroreCaratteri != "1" && $erroreVuoto != "1" && $erroreMail != "1" && $controlla == "si"&& $nonDescartes !="1")
{
  $tab='membri';
  $db = Database::connect();
  $result = $db->query("SELECT * FROM $tab WHERE email = '$email'");
  $logdb=$result->fetch();
  $num=$result->rowCount();
  //funzione che verifia se la mail è già registrata nel DB
  $usagerInesistente= "0";
  if($num<1)
  {
    $usagerInesistente= "1";
  }
  if($usagerInesistente != "1")
  {
    $loginbaby=$logdb['loginbaby'];
    $token=$logdb['token'];
      // Préparation du mail contenant le lien d'activation
      $destinataire = $email;
      $sujet = "Récuperation mot de passe" ;
      $entete = "From: info@desannales.com" ;
      // Le lien d'activation est composé du login(log) et de la clé(token)
      $message = 'Suivez ces étapes afin de récupérer votre mot de passe :
      Pour changer votre mot de passe, veuillez cliquer sur le lien ci-dessous
      ou copier/coller dans votre navigateur internet.

      http://desannales.com/motDePasseInit.php?log='.urlencode($loginbaby).'&token='.urlencode($token).'

      une fois cliqué vous pouvez désormais choisir votre nouveau mot de passe.

      Si vous avez reçu ce mail alors que vous n avez jamais fait la démande de récuperation
      ignorez-le simplement.

      ---------------
      Ceci est un mail automatique, Merci de ne pas y répondre.';


      mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail
      Database::disconnect();
      header("Location: login.php?recup=ok");
  }


    Database::disconnect();
    echo $num;



Database::disconnect();

}

function checkInput($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Accueil</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Special+Elite&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Lustria&display=swap" rel="stylesheet">
      <link rel = "icon" href ="./images/icona.png"type = "image/x-icon">
    <link rel="stylesheet" href="css/stileAccueil.css">
  </head>

<body>

  <div class="container">
    <div class="heading" style="font-weight:normal;"><h2><span class="iniziali">D</span>es <span class="iniziali">A</span>nnales</h2></div>
    <div class="row">
      <div class="col-lg-10 col-lg-offset-1">
        <form id="contact-form" action="" method="post" role="form">

          <div class="row">
            <div class="col-md-12">
              <label for="email">Email de Descartes</label>
              <input type="text" id = "email" name="email"  class="form-control" placeholder="Ex . ix0000@etu.parisdescartes.com" value="<?php $email ?>">
              <p class="comments"></p>
            </div>
            <input id="controlla" name="controlla" type="hidden" value="si"/>
              <input type="submit" class="button1" value="Confirmer">
            </div>

            <?php
            if($erroreCaratteri == "1")
              echo "<p style='text-align:center;margin-top:30px; color:red'>Caractère non autorisé</p>";
            if($erroreVuoto == "1")
                echo "<p style='text-align:center;margin-top:30px; color:red'>Vous n'avez pas rempli tous les champs</p>";
            else if($nonDescartes)
              echo "<p style='text-align:center;margin-top:30px; color:red'>Ce mail n'appartient pas a Descartes,essayez un mail de la forme <br> 'ix000000@etu.parisdescartes.fr'</p>";
            else if($usagerInesistente == "1")
              echo "<p style='text-align:center;margin-top:30px; color:red'>Ce mail n'appartient à acun compte </p>";
            else if($erroreMail == "1")
              echo "<p style='text-align:center;margin-top:30px; color:red'>Ce mail n'est pas valide</p>";
            ?>
          </div>

        </form>
      </div>
    </div>
  </div>

</body>
</html>
