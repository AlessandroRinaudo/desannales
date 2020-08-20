<?php
require 'admin-pannel/database.php';
$db = Database::connect();
$loginbaby = $_GET['log'];
$cle = $_GET['token'];

// Récupération de la clé correspondant au $loginbaby dans la base de données
// $stmt = $db->prepare("SELECT token,active FROM membri WHERE loginbaby like :loginbaby ");
$stmt = $db->prepare("SELECT token,active FROM membri WHERE loginbaby like :loginbaby ");
if($stmt->execute(array(':loginbaby' => $loginbaby)) && $row = $stmt->fetch())
  {
    $clebdd = $row['token'];	// Récupération de la clé
    $actif = $row['active']; // $actif contiendra alors 0 ou 1
  }


 ?>
 <!DOCTYPE html>
 <html lang="fr">
   <head>
     <title>Activation compte</title>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width,initial-scale=1">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
     <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="/css/style.css">
   </head>
   <body>
       <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
       <div class="container admin">
         <div class="row">
           <table class="table table-striped table-bordered">
             <?php
             if($actif == '1') // Si le compte est déjà actif on prévient
               {
                  echo "<p style='color:green;text-align:center;font-size:27px'>Votre compte est déjà actif !</p>";
               }
               else // Si ce n'est pas le cas on passe aux comparaisons
                 {
                    if($cle == $clebdd) // On compare nos deux clés
                      {
                         // Si elles correspondent on active le compte !
                         echo "<p style='color:green;text-align:center;font-size:27px'>Votre compte a bien été activé !</p>";

                         // La requête qui va passer notre champ actif de 0 à 1
                         $stmt = $db->prepare("UPDATE membri SET active = 1 WHERE loginbaby like :loginbaby");
                         $stmt->bindParam(':loginbaby', $loginbaby);
                         $stmt->execute();
                         //da testare
//                          $statement = $db->query('SELECT loginbaby FROM membri');
//                          $listaUtenti= "";
//                          while ($member=$statement->fetch())
//                          {
//                            $listaUtenti .= ' [OR] '. $member['loginbaby'] .' [NC] ';
//                          }
//                          $htaccessUtenti ='RewriteEngine On
// RewriteBase /
// RewriteCond %{HTTP_COOKIE} !loginbaby=aGfadNdzUygep6uf7bx6Wpo8wLLLyXdda1f8e9f3d087e9f2a4425479d14bad419b4380 [NC]'.$listaUtenti .'
// RewriteRule .* https://desannales.com/login.php?logout=si [L]';
//                          $f=fopen('link/.htaccess','wb');
//                          fwrite($f,$htaccessUtenti);
//                          fclose($f);
                         //fine da testare
                      }
                    else // Si les deux clés sont différentes on provoque une erreur...
                      {
                         echo "<p style='color:red;text-align:center;font-size:27px'>Erreur ! Votre compte ne peut être activé...</p>";
                      }
                 }
                 Database::disconnect();
            ?>
           </table>
         </div>
       </div>
   </body>
 </html>
