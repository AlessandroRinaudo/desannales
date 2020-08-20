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

    $nameError = $descriptionError = $categoryError = $imageError = $name = $description = $category = $image = "";
    if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
    {
      header("Location: login.php?logout=si");
    }////////
    else
    {
    if(!empty($_POST))
    {
        $name               = checkInput($_POST['name']);
        $description        = checkInput($_POST['description']);
        $category           = checkInput($_POST['category']);
        $isSuccess          = true;

        if(empty($name))
        {
            $nameError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }
        if(empty($description))
        {
            $descriptionError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if(empty($category))
        {
            $categoryError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }


        if ($isSuccess)
        {
            $db = Database::connect();

          $statement = $db->prepare("UPDATE subjects  set name = ?, description = ?, category = ? WHERE id = ?");
          $statement->execute(array($name,$description,$category,$id));

            Database::disconnect();
            header("Location: matieresListe.php");
        }
    }
    else
    {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM subjects where id = ?");
        $statement->execute(array($id));
        $subject = $statement->fetch();
        $name           = $subject['name'];
        $description    = $subject['description'];
        $category       = $subject['category'];
        $image          = $subject['image'];
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
                    <h1 style="text-align:center"><strong>Modifier une matière</strong></h1>
                    <br>
                    <form class="form" action="<?php echo 'update.php?id='.$id;?>" role="form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name" style="width:100%">Nom:
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="<?php echo $name;?>">
                            <span class="help-inline"><?php echo $nameError;?></span>
                        </div>
                        <div class="form-group">
                            <label for="description" style="width:100%">Description:
                            <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description;?>">
                            <span class="help-inline"><?php echo $descriptionError;?></span>
                        </div>


                        <div class="form-group">
                            <label for="category">Catégorie:
                            <select class="form-control" id="category" name="category">
                            <?php
                               $db = Database::connect();
                               foreach ($db->query('SELECT * FROM categories') as $row)
                               {
                                    if($row['id'] == $category)
                                        echo '<option selected="selected" value="'. $row['id'] .'">'. $row['name'] . '</option>';
                                    else
                                        echo '<option value="'. $row['id'] .'">'. $row['name'] . '</option>';;
                               }
                               Database::disconnect();
                            ?>
                            </select>
                            <span class="help-inline"><?php echo $categoryError;?></span>
                        </div>
                        <div class="form-group">
                            <label for="image">Image:</label>
                            <p><?php echo $image;?></p>
                            <!-- <label for="image">Sélectionner une nouvelle image:</label>
                            <input type="file" id="image" name="image"> -->
                            <span class="help-inline"><?php echo $imageError;?></span>
                        </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                            <a class="btn btn-primary" href="matieresListe.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                       </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
