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
          <h1><strong>Liste des Posts</strong> <a href="insertPost.php" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-plus"></span> Ajouter</a> <a href="index.php" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-book"></span> Menu</a> <a href="login.php?logout=si" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-arrow-left"></span> Logout</a>  </h1>
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Titre post</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

              <?php
                $statement = $db->query('SELECT * FROM articoli ORDER BY id DESC');
                while ($articolo=$statement->fetch())
                {
                  echo '<tr>';
                  echo '<td>'. $articolo['titolo'] .'</td>';
                  echo '<td width=300 style="text-align:center">';
                  echo  ' <a href="updatePost.php?id=' .$articolo['id'] . '" class="btn btn-info"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
                  echo  ' <a href="deletePost.php?id=' .$articolo['id'] . '" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Supprimer post</a>';
                  echo   '</td>';
                  echo '</tr>';
                }
                Database::disconnect();
               ?>
            </tbody>
          </table>
        </div>
      </div>
  </body>
</html>
