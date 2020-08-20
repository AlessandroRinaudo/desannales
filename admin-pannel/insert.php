<?php
///////////
$univoco = $_COOKIE['univoco'];

if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
{
  header("Location: login.php?logout=si");
}////////

require 'database.php';

$nameError = $descriptionError = $categoryError = $imageError = $name = $description = $category =  "";
$image ="b1.png";

if(!empty($_POST))
{
  $name               = checkInput($_POST['name']);
  $description        = checkInput($_POST['description']);
  $category           = checkInput($_POST['category']);
  $image              = checkInput($_POST['image']);
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
    // if(empty($category))
    // {
    //     $categoryError = 'Ce champ ne peut pas être vide';
    //     $isSuccess = false;
    // }
    // if(empty($image))
    // {
    //     $imageError = 'Ce champ ne peut pas être vide';
    //     $isSuccess = false;
    // }
    if($isSuccess)
    {
      $db = Database::connect();
      //////////
      if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
      {
        header("Location: login.php?logout=si");
      }////////
      else
      {
        $statement = $db->prepare("INSERT INTO subjects (name,description,category,image) values(?, ?, ?, ?)");
        $statement->execute(array($name,$description,$category,$image));
        Database::disconnect();
      }
      header("Location: matieresListe.php");
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
    <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
     <div class="container admin">
        <div class="row">
            <h1 style="text-align: center"><strong>Ajouter une matière</strong></h1>
            <br>
            <form class="form" action="insert.php" role="form" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nom:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nom matière" value="<?php echo $name;?>">
                    <span class="help-inline"><?php echo $nameError;?></span>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="ajouter une déscription" value="<?php echo $description;?>">
                    <span class="help-inline"><?php echo $descriptionError;?></span>
                </div>
                <div class="form-group">
                    <label for="category">Catégorie:</label>
                    <select class="form-control" id="category" name="category">
                    <?php
                       $db = Database::connect();
                       foreach ($db->query('SELECT * FROM categories') as $row)
                       {
                            echo '<option value="'. $row['id'] .'">'. $row['name'] . '</option>';;
                       }
                       Database::disconnect();
                    ?>
                    </select>
                    <span class="help-inline"><?php echo $categoryError;?></span>
                </div>
                <div class="form-group">
                    <label for="description">image:</label>
                    <input type="text" class="form-control" id="image" name="image" placeholder="ajouter une image ( Attention!!! l'image à saisir pour l'instant est b1.png )" value="<?php echo $image;?>">
                    <span class="help-inline"><?php echo $imageError;?></span>
                </div>
                <br>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Ajouter</button>
                    <a class="btn btn-primary" href="matieresListe.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
               </div>
            </form>
        </div>
    </div>
</body>
</html>
