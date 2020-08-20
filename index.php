<?php
$loginbaby = $_COOKIE['loginbaby'];

if($loginbaby == "" || preg_match("([<>&()%'?+])", $loginbaby) || preg_match('/"/', $loginbaby))
{
  header("Location: login.php?logout=si");
}

require 'admin-pannel/database.php';

$tab='membri';
$db = Database::connect();
$result = $db->query("SELECT id,nome,cognome,email FROM $tab WHERE loginbaby = '$loginbaby'");
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
    <title>Des Annales</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
      <link rel = "icon" href ="./images/icona.png"type = "image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <script>
    document.onreadystatechange = function () {
          setTimeout(function(){
             document.getElementById('load').style.visibility="hidden";
          },3000);
      }
    </script>
  </head>
  <body>
    <div class="container site">
      <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
      <?php
      	//require 'admin-pannel/database.php';
        echo '<nav>
                <ul class="nav nav-pills">';
      //  $db = Database::connect();
        $statement = $db->query('SELECT * FROM categories');
        $categories = $statement->fetchAll();
        foreach ($categories as $categoria)
        {
            if($categoria['id'] == '0')
                echo '<li role="presentation" class="active"><a href="#'. $categoria['id'] . '" data-toggle="tab">' . $categoria['name'] . '</a></li>';
            else
                echo '<li role="presentation"><a href="#'. $categoria['id'] . '" data-toggle="tab">' . $categoria['name'] . '</a></li>';
        }
        echo '<li role="presentation"><a href="envoyer-un-fichier.php" >Envoyer</a></li>';
        echo '<li role="presentation"><a href="modifierCompte.php" > Mon Compte</a></li>';
        echo '<li role="presentation"><a href="login.php?logout=si" > Logout </a></li>';

        echo    '</ul>
              </nav>';

        echo '<div class="tab-content">';

        foreach ($categories as $categoria)
        {
            if($categoria['id'] == '0')
                echo '<div class="tab-pane active" id="' . $categoria['id'] .'">';
            else
                echo '<div class="tab-pane" id="' . $categoria['id'] .'">';

            echo '<div class="row">';

            $statement = $db->prepare('SELECT * FROM subjects WHERE subjects.category = ?');
            $statement->execute(array($categoria['id']));
            while ($subjects = $statement->fetch())
            {
                if($categoria['id'] == '0')
                {
                  $richiesta=$db->query('SELECT * FROM articoli ORDER BY id DESC');

                   while ($articolo=$richiesta->fetch())
                   {
                     echo "
                     <div class='container admin' style='padding: 50px 0px;border-radius:20px;margin-bottom:10px' >
                     <div class='row'>";
                    echo "<h1 style='text-align:center;color:#0617D4;text-shadow:2px 2px #DFDEDE;padding:15px 40px;margin-right:20px'>".$articolo['titolo'] ."</h1>";
                    echo "   <p style='margin:10px 30px; padding:20px 50px;text-shadow:1px 1px #DFDEDE'>";
                    echo $articolo['paragrafo'];
                    echo  "</p>
                    </div>
                    </div>";
                   }

                }
                else
                {
                  echo '<div class="col-sm-4 col-md-3">
                          <div class="thumbnail">
                              <img src="images/' . $subjects['image'] . '" alt="image">'
                              .'<div class="caption">
                                  <h4>' . $subjects['name'] . '</h4>
                                  <p class="description_matiere">' . $subjects['description'] . '</p>
                                  <a  href="matiere.php?id=' .$subjects['id'] . '" class="btn btn-order" role="button"><span class="glyphicon glyphicon-open"></span> Ouvrir </a>
                              </div>
                          </div>
                      </div>';
                }

            }
           echo    '</div>
                </div>';
        }
        Database::disconnect();
        echo  '</div>';
       ?>
           <div id="load"></div>
      </div>



      </div>
    </div>
  </body>
</html>
