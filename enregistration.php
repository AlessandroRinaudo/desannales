<?php

$nome        = checkInput($_POST['nome']);
$cognome     = checkInput($_POST['cognome']);
$email       = checkInput($_POST['email']);
$passwordget = checkInput($_POST['password']);
$controlla   = checkInput($_POST['controlla']);

$password = sha1(md5(sha1($passwordget)));
/*Quello che fa questo script è semplicemente prendere l' ora attuale in
millisecondi crearla come variabile. Fine.*/
date_default_timezone_set("Europe/Rome");
$time = microtime(true);
$dFormat = "l jS F, Y - H:i:s";
$mSecs = $time - floor($time);
$mSecs = substr($mSecs,1);
$unique = sprintf('%s%s', date($dFormat), $mSecs );

/*Questo script genera una sequenza a dir suo casuale di 30 caratteri composti
da numeri e lettere maiuscole e minuscole. */
function generateRandomString($length = 30)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++)
  {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
$returnString = generateRandomString();

//$loginbaby sarà il codice identificativo del nostro utente che si registra e sarà una cosa del genere:
$loginbaby = $returnString.sha1($unique); //unisce le due variabili creati dai due script precedenti

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
  $num=$result->rowCount();
  //algoritmo che recupera il codice comune di sicurezza per consentire il collegamento tramite link diretto ai pdf
  $estraiCodiceSicurezza = $db->query("SELECT ulanrai FROM membri WHERE id=1");
  $ula=$estraiCodiceSicurezza->fetch();
  $ulanrei=$ula['ulanrai'];
  // fine di questo algoritmo
  if ($num > 0)
  {
    $erroreMailUsata = "1";
  }
  else
  {
    $aleatorio=rand("23456","65432");
    $token=md5($aleatorio);
    $result = $db->query("INSERT INTO $tab (nome, cognome, email, password,token,loginbaby,ulanrai) VALUES ('$nome','$cognome','$email','$password','$token','$loginbaby','$ulanrei')");

    // Préparation du mail contenant le lien d'activation
    $destinataire = $email;
    $sujet = "Activer votre compte" ;
    $entete = "From: info@desannales.com" ;
    // Le lien d'activation est composé du login(log) et de la clé(token)
    $message = 'Bienvenue sur Des Annales,

    Pour activer votre compte, veuillez cliquer sur le lien ci dessous
    ou copier/coller dans votre navigateur internet.

    http://desannales.com/activation.php?log='.urlencode($loginbaby).'&token='.urlencode($token).'


    ---------------
    Ceci est un mail automatique, Merci de ne pas y répondre.';


    mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail

    Database::disconnect();
    header("Location: login.php?reg=ok");
    echo "registrazione effettuata";

  }

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
            <div class="col-md-6">
              <label for="utente">Prénom</label>
              <input type="text" id = "nome"name="nome" class="form-control" placeholder="Ex. Jacques">
              <p class="comments"></p>
            </div>

            <div class="col-md-6">
              <label for="utente">Nom</label>
              <input type="text" id = "cognome"name="cognome" class="form-control" placeholder="Ex. Dupont">
              <p class="comments"></p>
            </div>

            <div class="col-md-6">
              <label for="email">Email de Descartes</label>
              <input type="text" id = "email"name="email"  class="form-control" placeholder="Ex . xx0000@etu.parisdescartes.com" >
              <p class="comments"></p>
            </div>

            <div class="col-md-6">
              <label for="password">Mot de Passe</label>
              <input type="password" id = "password"name="password"  class="form-control" placeholder="Choisissez votre mot de passe"  >
              <p class="comments"></p>
            </div>
            <input id="controlla" name="controlla" type="hidden" value="si"/>
            <div class="col-md-12">
              <input type="submit" class="button1" value="S'Enregistrer">
            </div>


          </div>
          <?php
          if($erroreCaratteri == "1")
            echo "<p style='text-align:center;margin-top:30px; color:red'>Caractère non autorisé</p>";
          if($nonDescartes)
            echo "<p style='text-align:center;margin-top:30px; color:red'>Ce mail n'appartient pas a Descartes,essayez un mail de la forme <br> 'ix000000@etu.parisdescartes.fr'</p>";
          else if($erroreVuoto == "1")
            echo "<p style='text-align:center;margin-top:30px; color:red'>Vous n'avez pas rempli tous les champs</p>";
          else if($erroreMail == "1")
            echo "<p style='text-align:center;margin-top:30px; color:red'>Ce mail n'est pas valide</p>";
          else if($erroreMailUsata == "1")
            echo "<p style='text-align:center;margin-top:30px; color:red'>Ce compte a été déja enregistré,si vous êtes le propetaire de ce compte verifiez votre boite mail</p>";
          ?>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
