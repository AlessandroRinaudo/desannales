<?php
///////////
$univoco = $_COOKIE['univoco'];

if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
{
  header("Location: login.php?logout=si");
}////////

require 'database.php';

$nameError = $matiereError = $typeError = $correctionError= $imageError = $fileCaricatoError = $name = $matiere = $type = $correction =$image ="";

if(!empty($_POST))
{
  $name               = checkInput($_POST['name']);
  $matiere            = checkInput($_POST['matiere']);
  $type               = checkInput($_POST['type']);
  $correction         = checkInput($_POST['correction']);
  $image              = checkInput($_POST['image']);
  $fileCaricato              = checkInput($_FILES["pdf_file"]["name"]);
  $isSuccess          = true;

  $allowedExts = array("pdf");
  $temp = explode(".", $_FILES["pdf_file"]["name"]);
  $extension = end($temp);
  // $upload_pdf=$_FILES["pdf_file"]["name"];
  $newfilename = md5(round(microtime(true))) . '.' . end($temp);
  $upload_pdf=$newfilename;
  move_uploaded_file($_FILES["pdf_file"]["tmp_name"],"../link/" . $newfilename);

    if(empty($name))
    {
        $nameError = 'Ce champ ne peut pas être vide';
        $isSuccess = false;
    }
    if(empty($fileCaricato))
    {
        $fileCaricatoError = 'Ce champ ne peut pas être vide';
        $isSuccess = false;
    }
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
        $statement = $db->prepare("INSERT INTO files (name,id_subject,type_cat,image,correction,link) values(?, ?, ?, ?, ?, ?)");
        $statement->execute(array($name,$matiere,$type,$image,$correction,$upload_pdf));
        Database::disconnect();
      }
        header("Location: fichiers.php");
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
            <h1 style="text-align: center"><strong>Ajouter un ficher</strong></h1>
            <br>
            <form class="form" action="insertFic.php" role="form" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nom:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nom du ficher" value="<?php echo $name;?>">
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
                </div>
                <div class="form-group">
                    <label for="type ">Type:</label>
                    <select class="form-control" id="type" name="type">
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

                </div>

                <div class="form-group">
                    <label for="correction">Corrigé:</label>
                    <select class="form-control" id="correction" name="correction">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                    </select>
                </div>
                <input type="file"  name="pdf_file" id="pdf_file" accept="application/pdf" />
                  <span class="help-inline"><?php echo $fileCaricatoError;?></span>
                <br>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Ajouter</button>
                    <a class="btn btn-primary" href="fichiers.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
               </div>
            </form>
        </div>
    </div>
</body>
</html>
