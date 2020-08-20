<?php
$univoco = $_COOKIE['univoco'];

if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
{
  header("Location: login.php?logout=si");
}

require 'database.php';

$tab='iscritti';
$db = Database::connect();
$result = $db->query("SELECT id,nome,cognome,email FROM $tab WHERE univoco = '$univoco'");
$num=$result->rowCount();
$logdb=$result->fetch();
if ($num < 1)
{
  header("Location: login.php?logout=si");
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Admin Pannel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
      <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
      <div class="container admin">
        <div class="row">             
          <a href="matieresListe.php"  class="btn btn-success btn-lg buttonsPA"><span class="glyphicon glyphicon-folder-open"></span> Matières</a> <br> <br> 
          <a href="fichiers.php" class="btn btn-warning btn-lg buttonsPA"><span class="glyphicon glyphicon-folder-close"></span> Fichers</a> <br> <br>
          <a href="posts.php" class="btn btn-danger btn-lg buttonsPA"><span class="glyphicon glyphicon-globe"></span> Posts</a> <br> <br>
          <a href="membres.php" class="btn btn-info btn-lg buttonsPA"><span class="glyphicon glyphicon-user"></span> Membres</a> <br> <br>
          <a href="validateurFichiers.php" class="btn btn-default btn-lg buttonsPA buttonsPA3"><span class="glyphicon glyphicon-duplicate"></span> Fichiers à valider</a> <br> <br>
          <a href="statistics.php" class="btn btn-default btn-lg buttonsPA"><span class="glyphicon glyphicon-signal"></span> Statistics</a> <br> <br>
          <a href="changeSecureSession.php" class="btn btn-default btn-lg buttonsPA buttonsPA2"><span class="glyphicon glyphicon-refresh"></span> Changer Code Secret</a> <br> <br>
          <a href="login.php?logout=si" class="btn btn-primary btn-lg buttonsPA"><span class="glyphicon glyphicon-arrow-left"></span> Logout</a>
      </div>
  </body>
</html>
