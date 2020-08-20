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
          <?php $statement = $db->query('SELECT id,nome,cognome,email,active FROM membri');
          $nf=$statement->rowCount(); ?>
          <h1><strong>Liste de membres : <?php echo $nf; ?> membres</strong> <a href="index.php" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-book"></span> Menu</a> <a href="login.php?logout=si" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-arrow-left"></span> Logout</a>  </h1>
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Etat</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

              <?php
                $statement = $db->query('SELECT id,nome,cognome,email,active FROM membri order by id desc');
                while ($member=$statement->fetch())
                {
                  echo '<tr>';
                  echo '<td>'. $member['nome'] .'</td>';
                  echo '<td>'. $member['cognome'] .'</td>';
                  echo  '<td>' . $member['email'] .'</td>';
                  if($member['active']==1)
                    echo '<td>Actif</td>';
                  else
                    echo '<td>Désactive</td>';
                  echo '<td width=300 style="text-align:center">';
                  echo  ' <a href="updateMember.php?id=' .$member['id'] . '" class="btn btn-info"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
                  echo  ' <a href="deleteMember.php?id=' .$member['id'] . '" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Supprimer membre</a>';
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
