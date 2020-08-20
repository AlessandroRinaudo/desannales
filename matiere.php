<?php
///////////
$loginbaby = $_COOKIE['loginbaby'];

if($loginbaby == "" || preg_match("([<>&()%'?+])", $loginbaby) || preg_match('/"/', $loginbaby))
{
  header("Location: login.php?logout=si");
}////////
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
  </head>
  <body>
    <div class="container site">
      <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
       <a class="btn btn-success"href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour </a> <br>
      <?php
      require 'admin-pannel/database.php';
      if(!empty($_GET['id']))
      {
        $id =ceckInput($_GET['id']);
      }
      $db = Database::connect();
      $statement= $db->prepare('SELECT subjects.id, subjects.name,subjects.description FROM subjects WHERE subjects.id = ?');
      $statement->execute(array($id));
      $file =$statement->fetch();
      echo '<h2 class="nome-materia"> ' .$file['name'] . '</h2> <br>';
      echo '<p class="description_mat">' .$file['description'] . '<p>';



      echo '<nav>
              <ul class="nav nav-pills">';

      $statement = $db->query('SELECT * FROM type');
      $tipologia = $statement->fetchAll();
      foreach ($tipologia as $categoria)
      {
          if($categoria['id'] == '1')
              echo '<li role="presentation" class="active"><a href="#'. $categoria['id'] . '" data-toggle="tab">' . $categoria['name'] . '</a></li>';
          else
              echo '<li role="presentation"><a href="#'. $categoria['id'] . '" data-toggle="tab">' . $categoria['name'] . '</a></li>';
      }

      echo    '</ul>
            </nav>';

      echo '<div class="tab-content">';

      foreach ($tipologia as $categoria)
      {
          if($categoria['id'] == '1')
              echo '<div class="tab-pane active" id="' . $categoria['id'] .'">';
          else
              echo '<div class="tab-pane" id="' . $categoria['id'] .'">';

          echo '<div class="row">';

          $statement = $db->prepare('SELECT * FROM files WHERE files.type_cat = ? AND files.id_subject='.$id);
          $statement->execute(array($categoria['id']));
          while ($file = $statement->fetch())
          {
            if($file['correction']=='1')
            {
              echo '<div class="col-sm-4 col-md-3">
                    <div class="thumbnail">
                        <img src="images/' . $file['image'] . '" alt="image">
                         <div class="price">corrigé  </div>
                      <div class="caption">
                            <h4>' . $file['name'] . '</h4>
                            <p class="description_matiere">' . $file['description'] . '</p>';
                            if(strpos($file['link'],'.pdf')>1)
                              echo' <a  href="link/viewer.php?file='. $file['link'] .'" target="_blank" class="btn btn-order" role="button"><span class="glyphicon glyphicon-open"></span> Ouvrir </a>';
                            else
                              echo' <a  href="link/'. $file['link'] .'" download target="_blank" class="btn btn-order" role="button"><span class="glyphicon glyphicon-open"></span> Ouvrir </a>';
                      echo' </div>
                    </div>
                </div>';
              }
              else
              {
                echo '<div class="col-sm-4 col-md-3">
                        <div class="thumbnail">
                            <img src="images/' . $file['image'] . '" alt="image">
                          <div class="caption">
                                <h4>' . $file['name'] . '</h4>
                                <p class="description_matiere">' . $file['description'] . '</p>';
                                if(strpos($file['link'],'.pdf')>1)
                                  echo' <a  href="link/viewer.php?file='. $file['link'] .'" target="_blank" class="btn btn-order" role="button"><span class="glyphicon glyphicon-open"></span> Ouvrir </a>';
                                else
                                  echo' <a  href="link/'. $file['link'] .'" download target="_blank" class="btn btn-order" role="button"><span class="glyphicon glyphicon-open"></span> Ouvrir </a>';
                          echo' </div>
                        </div>
                    </div>';
              }
          }
         echo    '</div>
              </div>';
      }
      Database::disconnect();
      echo  '</div>';
      function ceckInput($data)
      {
        $data =trim($data);
        $data =stripslashes($data);
        $data =htmlspecialchars($data);
        return $data;
      }
       ?>
    </div>
  </body>
</html>
