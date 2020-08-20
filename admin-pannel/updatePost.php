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

    $titre =$titreError=$paraghraphe =$paraghrapheError ="";
    if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
    {
      header("Location: login.php?logout=si");
    }////////
    else
    {
    if(!empty($_POST))
    {
        $titre              = checkInput($_POST['titre']);
        $paraghraphe         = checkInput($_POST['paraghraphe']);
        $isSuccess           = true;

        if(empty($titre))
        {
            $titreError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($paraghraphe))
        {
            $paraghrapheError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if ($isSuccess)
        {
            $db = Database::connect();

          $statement = $db->prepare("UPDATE articoli  set titolo = ?, paragrafo = ? WHERE id = ?");
          $statement->execute(array($titre,$paraghraphe,$id));

            Database::disconnect();
            header("Location: posts.php");
        }
    }
    else
    {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM articoli where id = ?");
        $statement->execute(array($id));
        $articolo = $statement->fetch();
        $titre          = $articolo['titolo'];
        $paraghraphe = $articolo['paragrafo'];
        Database::disconnect();
    }
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
        <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
    </head>

    <body>
        <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
         <div class="container admin">
            <div class="row">
                <div class="col-sm-12">
                    <h1 style="text-align:center"><strong>Modifier un post</strong></h1>
                    <br>
                    <form class="form" action="<?php echo 'updatePost.php?id='.$id;?>" role="form" method="post" enctype="multipart/form-data">
                      <p style="text-align:center;color:red; font-size:20px;"></p>

                      <div class="form-group">
                          <label for="titre">Titre:</label>
                          <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du post" value="<?php echo $titre;?>">
                          <span class="help-inline"><?php echo $titreError;?></span>
                      </div>

                      <div class="form-group">
                          <label for="paraghraphe">Post:</label>
                            <textarea style='text-align:justify'rows="10" cols="50" class="form-control" id="paraghraphe" name="paraghraphe" placeholder="Ecrire le post..."><?php echo $paraghraphe;?>
                            </textarea>
                          <span class="help-inline"><?php echo $paraghrapheError;?></span>
                      </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                            <a class="btn btn-primary" href="posts.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                       </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
