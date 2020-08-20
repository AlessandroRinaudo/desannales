<?php
require 'admin-pannel/database.php';
$db = Database::connect();
$loginbaby = $_GET['log'];
$cle = $_GET['token'];

// Récupération du mot de passe correspondant au $loginbaby dans la base de données
$stmt = $db->prepare("SELECT password,token FROM membri WHERE loginbaby like :loginbaby ");
if($stmt->execute(array(':loginbaby' => $loginbaby)) && $row = $stmt->fetch())
  {
    $num=$stmt->rowCount(); //aggiunto mo
    $clebdd = $row['token'];	// Récupération de la clé
    $password = $row['password']; // $password contiendre le vieux mot de passe cripté
  }
  $pass = $passwordError = "";
  $isSuccess =true;
  if(!empty($_POST))
  {
      $pass = checkInput($_POST['pass']);
      if(empty($pass))
      {
          $passwordError = 'Ce champ ne peut pas être vide';
          $isSuccess = false;
      }
    if ($isSuccess)
    {
      if(!empty($pass))
      {
        $statement = $db->prepare("UPDATE membri set password= ? WHERE loginbaby = ?");
        $statement->execute(array(sha1(md5(sha1($pass))),$loginbaby));
            header("Location: index.php");
      }
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
 <html lang="fr">
   <head>
     <title>Nouveau mot de passe </title>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width,initial-scale=1">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
     <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="css/style.css">
   </head>
   <body>
       <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>

                <?php
                if($num < 1)
                  echo "<p style='text-align:center;font-size:50px; color:#fff;' class='site'>La page que vous <br>demandez est introuvable</p>";
                else if($cle == $clebdd)
                {
                  echo '
                  <div class="container admin">
                     <div class="row">
                         <div class="col-sm-12">
                  <h1 style="text-align:center"><strong>Réinitialiser mot de passe </strong></h1>
                  <br>
                  <form class="form" action="motDePasseInit.php?log=' .$loginbaby .'&token='.$cle . '" role="form" method="post" encpassword="multipart/form-data">
                      <div class="form-group">
                          <label for="pass" style="width:100%">Nouveau mot de passe:</label>
                          <input type="password" class="form-control" id="pass" name="pass" placeholder="">
                          <span class="help-inline"><?php echo $passwordError;?></span>
                      </div>

                      <br>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Confirmer</button>
                     </div>
                  </form>
                  </div>
              </div>';
                }
                else
                {
                  echo "<p style='text-align:center;font-size:50px; color:#fff;' class='site'>La page que vous <br>demandez est introuvable</p>";
                }
                 ?>



      </div>
   </body>
 </html>
