<?php

if($_GET['logout'] == "si" || $_GET['reg'] == "ok")
{
  setcookie("univoco", "",time() + 1800);
}
else if($_COOKIE['univoco'] != "")
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

require 'database.php';
$tab='iscritti';
if ($erroreCaratteri != "1" && $erroreVuoto != "1" && $erroreMail != "1" && $controlla == "si")
{
  $db = Database::connect();
  $result = $db->query("SELECT univoco FROM $tab WHERE email = '$email' AND password = '$password'");
  $num=$result->rowCount();
  $logdb=$result->fetch();
  Database::disconnect();

  $univoco = $logdb['0'];

  if ($num < 1)
  {
    $erroreNonEsiste = "1";
  }
  else
  {
    setcookie("univoco", $univoco,time() + 1800);
    header("Location: index.php");
  }

}
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Reg Grafica</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Lustria&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Special+Elite&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/loginStyle.css">
  </head>
	<body>

		<div class="container">
			<div class="heading" style="font-weight:normal;"><h2><span class="iniziali">A</span>dmin <span class="iniziali">P</span>anel</h2></div>
			<div class="row">
				<div class="col-lg-10 col-lg-offset-1">
					<form id="contact-form" action="?" method="post" role="form">

            <div class="row">
							<div class="col-md-12">
								<label for="email">E-mail</label>
								<input type="text" id = "email"name="email" class="form-control" placeholder="Email admin">
								<p class="comments"></p>
							</div>


							<div class="col-md-12">
								<label for="password">Mot de Passe</label>
								<input type="password" id = "password"name="password"  class="form-control" placeholder="Mot de passe admin" >
								<p class="comments"></p>
							</div>
              <input id="controlla" name="controlla" type="hidden" value="si"/>
              <div class="col-md-12">
                <input type="submit" class="button1" value="Se connecter">
              </div>


						</div>
            <?php
            if($erroreCaratteri == "1")
            {
              echo "<p style='text-align:center;margin-top:30px; color:red'>Vous avez tenté d'utiliser des caractères spéciaux</p>";
              echo "<p style='margin-top:20px;font-size:12px'>panel d'administration Des Annales ©</p>";
            }
              
            if($erroreVuoto == "1")
            {
              echo "<p style='text-align:center ;margin-top:30px;color:red'>Vous avez laissé des champs vides</p>";
              echo "<p style='margin-top:20px;font-size:12px'>panel d'administration Des Annales ©</p>";
            }
              
            else if($erroreMail == "1")
            {
              echo "<p style='text-align:center;margin-top:30px;color:red'>Mail invalid</p>";
              echo "<p style='margin-top:20px;font-size:12px'>panel d'administration Des Annales ©</p>";
            }
            else if($erroreNonEsiste == "1")
            {
              echo "<p style='text-align:center;margin-top:30px;color:red'>Cet Admin n'existe pas </p>";
              echo "<p style='margin-top:20px;font-size:12px'>panel d'administration Des Annales ©</p>";
            }       
            else if($_GET['reg'] == "ok")
              echo "<p style='text-align:center;margin-top:30px;color:red'>Registrazione effettuata, puoi accedere</p>";
            else if($_GET['logout'] == "si")
              echo "<p style='margin-top:20px;font-size:12px'>panel d'administration Des Annales ©</p>";
            ?>
					</form>
				</div>
			</div>
		</div>

	</body>
</html>
