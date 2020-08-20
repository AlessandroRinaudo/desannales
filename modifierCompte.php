<?php
///////////
$loginbaby = $_COOKIE['loginbaby'];

if($loginbaby == "" || preg_match("([<>&()%'?+])", $loginbaby) || preg_match('/"/', $loginbaby))
{
  header("Location: login.php?logout=si");
}////////

    require 'admin-pannel/database.php';

    $id = $_COOKIE['loginbaby'];

    $nomeError = $cognomeError = $passwordError = $activeError = $nome = $cognome = $password = $active ="";
    if($loginbaby == "" || preg_match("([<>&()%'?+])", $loginbaby) || preg_match('/"/', $loginbaby))
    {
      header("Location: login.php?logout=si");
    }////////
    else
    {
     if(!empty($_POST))
     {
        $nome               = checkInput($_POST['name']);
        $cognome            = checkInput($_POST['secondName']);
        $password           = checkInput($_POST['pass']);
        if($active==0)
          $active             = checkInput($_POST['active']);
        $isSuccess          = true;

        if(empty($nome))
        {
            $nomeError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }
        if(empty($cognome))
        {
            $cognomeError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }
        if ($isSuccess)
        {
            $db = Database::connect();

          if(!empty($password))
          {
            $statement = $db->prepare("UPDATE membri set nome = ?, cognome = ?,password= ? WHERE loginbaby = ?");
            $statement->execute(array($nome,$cognome,sha1(md5(sha1($password))),$id));
          }
          else
          {
            $statement = $db->prepare("UPDATE membri set nome = ?, cognome = ? WHERE loginbaby = ?");
            $statement->execute(array($nome,$cognome,$id));
          }
            Database::disconnect();
            header("Location: index.php");
        }
     }
    else
    {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM membri where loginbaby = ?");
        $statement->execute(array($id));
        $member = $statement->fetch();
        $nome           = $member['nome'];
        $cognome        = $member['cognome'];
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
        <title>Modifier Profil</title>
        <meta charset="utf-8"/>
        <meta nome="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
        <link rel = "icon" href ="./images/icona.png"type = "image/x-icon">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
         <div class="container admin">
            <div class="row">
                <div class="col-sm-12">
                    <h1 style="text-align:center"><strong>Modifier compte</strong></h1>
                    <br>
                    <form class="form" action="<?php echo 'modifierCompte.php'?>" role="form" method="post" encpassword="multipart/form-data">

                        <div class="form-group">
                            <label for="name" style="width:100%">Prénom:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Ex Jacques" value="<?php echo $nome;?>">
                            <span class="help-inline"><?php echo $nomeError;?></span>
                        </div>
                        <div class="form-group">
                            <label for="secondName" style="width:100%">Nom:</label>
                            <input type="text" class="form-control" id="secondName" name="secondName" placeholder="Ex Dupont" value="<?php echo $cognome;?>">
                            <span class="help-inline"><?php echo $cognomeError;?></span>
                        </div>

                        <div class="form-group">
                            <label for="pass" style="width:100%">Nouveau mot de passe:</label>
                            <input type="password" class="form-control" id="pass" name="pass" placeholder="" value="<?php echo $password;?>">
                            <span class="help-inline"><?php echo $passwordError;?></span>
                        </div>

                        <br>
                        <div class="form-actions">
                            <button password="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                            <a href="supprimerCompte.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Supprimer compte</a>
                            <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                       </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
