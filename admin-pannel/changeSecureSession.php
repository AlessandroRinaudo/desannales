<?php
///////////
$univoco = $_COOKIE['univoco'];

if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
{
  header("Location: login.php?logout=si");
}////////

require 'database.php';

$db = Database::connect();
$result = $db->query("SELECT ulanrai FROM membri WHERE id=1");
$ula=$result->fetch();
$cambiami=$ula['ulanrai'];

if(!empty($_POST))
{
    $cambiami = checkInput($_POST['cambiami']);
    $cambiami=sha1(md5(sha1($cambiami))).md5($cambiami);

    if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
    {
      header("Location: login.php?logout=si");
    }////////
    else
    {
      $richiesta='UPDATE membri SET ulanrai= ? WHERE 1';
      $statement = $db->prepare($richiesta);
      $statement->execute(array($cambiami));
      Database::disconnect();
      //da testare
      $f=fopen('../link/.htaccess','wb');
      $contenuto='RewriteEngine On
RewriteBase /
RewriteCond %{HTTP_COOKIE} !ulonrei='.$cambiami.' [NC]
RewriteRule .* https://desannales.com/login.php?logout=si [L]
<FilesMatch "\.(jpg|zip|avi|pdf)$" >
    ForceType application/octet-stream
    Header add Content-Disposition "attachment"
</FilesMatch>';
      fwrite($f,$contenuto);
      fclose($f);
      // fine da testare

    }
    header("Location: index.php");
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
      <div class="container admin" style="text-align:center">
         <div class="row">
             <h1><strong>Changer Secure Code</strong></h1>
             <br>
             <form class="form" action="changeSecureSession.php" role="form" method="post">
                 <input type="hidden" name="cambiami" value="<?php echo $cambiami;?>"/>
                 <p class="alert alert-warning">Etes vous sur de vouloir changer le code secret ? <br> Une fois changé le code de sécurité, tout le monde sera déconnecté </p>
                 <div class="form-actions">
                   <button type="submit" style="font-size:30px"class="btn btn-warning">Oui</button>
                   <a class="btn btn-default" style="font-size:30px" href="index.php">Non</a>
                 </div>
             </form>
         </div>
     </div>
  </body>
</html>
