<?php
///////////
$loginbaby = $_COOKIE['loginbaby'];

if($loginbaby == "" || preg_match("([<>&()%'?+])", $loginbaby) || preg_match('/"/', $loginbaby))
{
  header("Location: login.php?logout=si");
}////////

    require 'admin-pannel/database.php';
    $id = $_COOKIE['loginbaby'];

    $db = Database::connect();
    $statement = $db->prepare("SELECT email FROM membri WHERE loginbaby = ? ");
    $statement->execute(array($id));
    $res=$statement->fetch();
    $id=$res['email'];
    Database::disconnect();


$nameError = $matiereError = $typeError = $correctionError = $fileCaricatoError = $name = $matiere = $type = $correction = "";

if(!empty($_POST))
{
  $name               = checkInput($_POST['name']);
  $matiere            = checkInput($_POST['matiere']);
  $type               = checkInput($_POST['type']);
  $correction         = checkInput($_POST['correction']);
  $fileCaricato       = checkInput($_FILES["pdf_file"]["name"]);
  $isSuccess          = true;

  //pezzo di codice che serve ad inserie un pdf rinominato con un codice univoco all'interno del database
  $allowedExts = array("pdf");
  $temp = explode(".", $_FILES["pdf_file"]["name"]);
  $extension = end($temp);
  $newfilename = md5(round(microtime(true))) . '.' . end($temp);
  $upload_pdf=$newfilename;
  move_uploaded_file($_FILES["pdf_file"]["tmp_name"],"link/" . $newfilename);
  //fine pezzo di codice che serve ad inserie un pdf rinominato con un codice univoco all'interno del database

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
      if($loginbaby == "" || preg_match("([<>&()%'?+])", $loginbaby) || preg_match('/"/', $loginbaby))
      {
        header("Location: login.php?logout=si");
      }////////
      else
      {
        $statement = $db->prepare("INSERT INTO filesSends (name,id_subject,type_cat,correction,link,contributeur) values(?, ?, ?, ?, ?, ?)");
        $statement->execute(array($name,$matiere,$type,$correction,$upload_pdf,$id));
        Database::disconnect();

             // Préparation du mail de confirmation envoi du fichier
             $destinataire = $id;
             $sujet = "Confirmation envoi du fichier" ;
             $entete = "From: info@desannales.com";
             $message = "Vous avez bien envoye votre fichier PDF qui est en attente d'evaluation par nos moderateurs.\n L'equipe des Annales vous remercie pour votre collaboration. \n\n--------------\nCeci est un mail automatique, Merci de ne pas y repondre.";
          mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail
      }
        header("Location: index.php");
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
    <title>Envoyer un Fichier</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Marck+Script&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1 class="text-logo"><span class="glyphicon glyphicon-folder-open"></span> <span class="neretto">D</span>es <span class="neretto">A</span>nnales <span class="glyphicon glyphicon-folder-open" ></span></h1>
     <div class="container admin">
        <div class="row">
            <h1 style="text-align: center"><strong>Envoyer un ficher</strong></h1>
            <br>
            <form class="form" action="envoyer-un-fichier.php" role="form" method="post" enctype="multipart/form-data">
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
                    <label for="correction">Corrigé:</label>
                    <select class="form-control" id="correction" name="correction">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                    </select>
                </div>
                <input type="file"  name="pdf_file" id="pdf_file" accept="application/pdf" />
                  <span class="help-inline"><?php echo $fileCaricatoError;?></span>
                  <a href="pdf-only.php">avant d'envoyer le fichier lire ici</a>
                <br>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-send"></span> Envoyer</button>
                    <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
               </div>
            </form>
        </div>
    </div>
</body>
</html>
