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

    $nomeError = $cognomeError = $passwordError = $activeError = $nome = $cognome = $password = $active ="";
    if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
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
            $statement = $db->prepare("UPDATE membri set nome = ?, cognome = ?,password= ? ,active= ?  WHERE id = ?");
            $statement->execute(array($nome,$cognome,sha1(md5(sha1($password))),$active,$id));
          }
          else
          {
            $statement = $db->prepare("UPDATE membri set nome = ?, cognome = ?,active= ?  WHERE id = ?");
            $statement->execute(array($nome,$cognome,$active,$id));
          }
            Database::disconnect();
            header("Location: membres.php");
        }
     }
    else
    {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM membri where id = ?");
        $statement->execute(array($id));
        $member = $statement->fetch();
        $nome           = $member['nome'];
        $cognome        = $member['cognome'];
        $active         = $member['active'];
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
        <meta nome="viewport" content="width=device-width, initial-scale=1">
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
                    <h1 style="text-align:center"><strong>Modifier un membre</strong></h1>
                    <br>
                    <form class="form" action="<?php echo 'updateMember.php?id='.$id;?>" role="form" method="post" encpassword="multipart/form-data">

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

                          <div class="form-group">
                              <label for="active">Active:</label>
                              <select class="form-control" id="active" name="active">
                              <option value="1">Oui</option>
                              <option value="0">Non</option>
                              </select>
                          </div>
                          

                        <br>
                        <div class="form-actions">
                            <button password="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                            <a class="btn btn-primary" href="membres.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                       </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
