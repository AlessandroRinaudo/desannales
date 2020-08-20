<?php

if($_GET['logout'] == "si" || $_GET['reg'] == "ok" || $_GET['recup'] == "ok")
{
  setcookie("loginbaby", "",time() + 1800);
  //resetta cookie che blocca le pagine pdf
  setcookie("ulonrei", "",time() + 1800);
}
else if($_COOKIE['loginbaby'] != "")
{
  header("Location: index.php");
}

$email = $_POST['email'];
$passwordget = $_POST['password'];
$controlla = $_POST['controlla'];
$password = sha1(md5(sha1($passwordget)));

if($controlla == "si")
{
  foreach($_POST as $key=>$value)
  {
    if (preg_match("(([<>?+'%&()]),", $value) || preg_match('/"/', $value))
      $erroreCaratteri = "1";
    if ($value == "")
      $erroreVuoto = "1";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    $erroreMail = "1";
}

require 'admin-pannel/database.php';
$tab='membri';
if ($erroreCaratteri != "1" && $erroreVuoto != "1" && $erroreMail != "1" && $controlla == "si")
{
  $db = Database::connect();
  $result = $db->query("SELECT loginbaby,active,ulanrai FROM $tab WHERE email = '$email' AND password = '$password'");
  $num=$result->rowCount();
  $logdb=$result->fetch();
  Database::disconnect();


  $loginbaby = $logdb['loginbaby'];

  $attivo=$logdb['active'];
  $ulanrai = $logdb['ulanrai'];
  if ($num < 1)
  {
    $db = Database::connect();
    $result = $db->query("SELECT loginbaby,active,ulanrai FROM $tab WHERE email = '$email'");
    $almenoEmailGiusta=$result->rowCount();
    Database::disconnect();
    if($almenoEmailGiusta==1)
      $passwordnonEsistente ="1";
    else
      $erroreNonEsiste = "1";
  }
   else if($attivo== 0)
     $accountNonAttivo ="1";
  else
  {
    setcookie("loginbaby", $loginbaby,time() + 1800);
    setcookie("ulonrei", $ulanrai,time() + 1800);

    //pezzo di codice che serve a registrare l'ora di login di ogni utente
    $db = Database::connect();
    date_default_timezone_set('Europe/Paris');
    $statement = $db->prepare("SELECT email FROM membri WHERE loginbaby= ?");
    $statement->execute(array($loginbaby));
    $risultato = $statement->fetch();
    $emailUtente=$risultato['email'];
    $numeroStudente=strtok($emailUtente,'@');
    $ora =date("h:i a");
    $attivita=$numeroStudente.' se connecte au site à '.$ora;
    $dataDiConnessione=date("d");
    $statement = $db->prepare("INSERT INTO activity (email,attivita,giorno) values(?,?,?)");
    $statement->execute(array($numeroStudente,$attivita,$dataDiConnessione));
    
    // la variabile $cancella verifica se l'ultima persona connessa si è collegata un nuovo giorno,in questo caso tutte le ore di connessioni precedenti vengono eliminate
    $cancella = $db->prepare("DELETE FROM `activity` WHERE NOT giorno = ?");
    $cancella->execute(array($dataDiConnessione));
    Database::disconnect();
    //fine pezzo di codice che serve a registrare l'ora di login di ogni utente
    header("Location: index.php");
  }

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
					<form id="contact-form" action="?" method="post" role="form">

            <div class="row">
							<div class="col-md-12">
								<label for="email">E-mail Descartes</label>
								<input type="text" id = "email"name="email" class="form-control" placeholder="Ex. ix00000@etu.parisdescartes.fr">
								<p class="comments"></p>
							</div>


							<div class="col-md-12">
								<label for="password">Mot de passe</label>
								<input type="password" id = "password"name="password"  class="form-control" placeholder="Mot de passe Ex. 000000" >
								<p class="comments"></p>
							</div>
              <input id="controlla" name="controlla" type="hidden" value="si"/>
              <div class="col-md-6">
                <input type="submit" class="button1" value="Me connecter">
              </div>
							<div class="col-md-6">
								<a class="button2" href="enregistration.php" disabled>S'enregistrer</a>
							</div>


						</div>
            <?php
            if($erroreCaratteri == "1")
              echo "<p style='text-align:center;margin-top:30px; color:red'>Caractère non autorisé</p>";
            else if($erroreVuoto == "1")
              echo "<p style='text-align:center;margin-top:30px;color:red'>Vous n'avez pas rempli tous les champs</p>";
            else if($erroreMail == "1")
              echo "<p style='text-align:center;margin-top:30px;color:red'>Ce mail n'est pas valide</p>";
            else if($passwordnonEsistente == "1")
              echo "<p style='text-align:center;margin-top:30px;color:red'> Mot de passe incorrect</p>";
            else if($erroreNonEsiste == "1")
              echo "<p style='text-align:center;margin-top:30px;color:red'>Cet usager n'existe pas </p>";
            else if($accountNonAttivo=="1")
               echo "<p style='text-align:center;margin-top:30px;color:red'>Ce compte n'est pas active , verifiez votre boite mail</p>";

             if($_GET['reg'] == "ok")
              echo "<p style='text-align:center;margin-top:30px;color:green'>Enregistration effectuée avec suxcès , pour activer votre compte verifiez votre boîte mail</p>";
              if($_GET['recup'] == "ok")
                  echo "<p style='text-align:center;margin-top:30px;color:green;'> Demande de récuperation effectuée avec suxcès verifiez votre boîte mail</a></p>";
             if($_GET['logout'] == "si")
              echo "<br><p style='text-align:center;margin-top:12px;'><a href='mdpperdu.php'>Mot de passe oublié</a>  |   <a href='mailto:info@desannales.com'>Contactez nous</a></p>";
            ?>
					</form>
				</div>
			</div>
		</div>

	</body>
</html>
