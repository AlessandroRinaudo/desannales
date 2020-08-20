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

    $nameError = $matiereError = $typeError = $correctionError= $imageError = $name = $matiere = $type = $correction =$image ="";
    if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
    {
      header("Location: login.php?logout=si");
    }////////
    else
    {
    if(!empty($_POST))
    {
        $name               = checkInput($_POST['name']);
        $matiere            = checkInput($_POST['matiere']);
        $type               = checkInput($_POST['type']);
        $correction         = checkInput($_POST['correction']);
        $image              = checkInput($_POST['image']);
        $isSuccess          = true;

        if(empty($name))
        {
            $nameError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if ($isSuccess)
        {
            $db = Database::connect();

          $statement = $db->prepare("UPDATE filesSends  set name = ?, id_subject = ?, type_cat = ?, image= ? ,correction= ?  WHERE id = ?");
          $statement->execute(array($name,$matiere,$type,$image,$correction,$id));

            Database::disconnect();
            header("Location: validateurFichiers.php");
        }
    }
    else
    {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM filesSends where id = ?");
        $statement->execute(array($id));
        $file = $statement->fetch();
        $name           = $file['name'];
        $description    = $file['description'];
        $category       = $file['category'];
        $type           = $file['type_cat'];
        $image          = $file['image'];
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
                    <h1 style="text-align:center"><strong>Modifier un ficher <?php echo $type ?></strong></h1>
                    <br>
                    <form class="form" action="<?php echo 'updateFicAValider.php?id='.$id;?>" role="form" method="post" enctype="multipart/form-data">
                      <p style="text-align:center;color:red; font-size:20px;">Faire très attention aux champs avant de changer!!!!!</p>
                        <div class="form-group">
                            <label for="name" style="width:100%">Nom:
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="<?php echo $name;?>">
                            <span class="help-inline"><?php echo $nameError;?></span>
                        </div>
                        <div class="form-group">
                            <label for="type ">Matière:</label>
                            <select class="form-control" id="matiere" name="matiere">
                            <?php
                               $db = Database::connect();
                               foreach ($db->query('SELECT * FROM subjects') as $row)
                               {
                                    echo '<option value="'. $row['id'] .'">'. $row['name'] . '</option>';;
                               }
                               Database::disconnect();
                            ?>
                            </select>
                            <span class="help-inline"><?php echo $matiereError;?></span>
                        </div>

                        <div class="form-group">
                            <label for="type ">Type:</label>
                            <select class="form-control" id="type" name="type" value ="<?php echo $type ?>">
                            <?php
                            $db = Database::connect();
                               foreach ($db->query('SELECT * FROM type') as $row)
                               {
                                    echo '<option value="'. $row['id'] .'">'. $row['name'] . '</option>';;
                               }
                               Database::disconnect();
                            ?>
                            </select>
                            <span class="help-inline"><?php echo $typeError;?></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <select class="form-control" id="image" name="image">
                            <?php
                               $db = Database::connect();
                               foreach ($db->query('SELECT * FROM image') as $row)
                               {
                                    echo '<option value="'. $row['name'] . '">'. $row['name'] . '</option>';
                               }
                               Database::disconnect();
                            ?>
                            </select>
                            <span class="help-inline"><?php echo $imageError;?></span>
                        </div>

                        <div class="form-group">
                            <label for="correction">Corrigé:</label>
                            <select class="form-control" id="correction" name="correction">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                            </select>
                            <span class="help-inline"><?php echo $imageError;?></span>
                        </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                            <a class="btn btn-primary" href="fichiers.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                       </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
