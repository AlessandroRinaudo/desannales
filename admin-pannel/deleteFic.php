<?php
///////////
$univoco = $_COOKIE['univoco'];

if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
{
  header("Location: login.php?logout=si");
}////////

    require 'database.php';

    if(!empty($_GET['id']))
        $id = checkInput($_GET['id']);

    if(!empty($_POST))
    {
        $id = checkInput($_POST['id']);
        $db = Database::connect();
        if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
        {
          header("Location: login.php?logout=si");
        }////////
        else
        {
          $statement = $db->prepare("DELETE FROM files WHERE id = ?");
          $statement->execute(array($id));
          Database::disconnect();
        }

        header("Location: fichiers.php");
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
<html>
    <head>
        <title>Admin Pannel</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
      <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
         <div class="container admin">
            <div class="row">
                <h1><strong>Supprimer un Ficher</strong></h1>
                <br>
                <form class="form" action="deleteFic.php" role="form" method="post">
                    <input type="hidden" name="id" value="<?php echo $id;?>"/>
                    <p class="alert alert-warning">Etes vous sur de vouloir supprimer ce ficher?</p>
                    <div class="form-actions">
                      <button type="submit" class="btn btn-warning">Oui</button>
                      <a class="btn btn-default" href="fichiers.php">Non</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
