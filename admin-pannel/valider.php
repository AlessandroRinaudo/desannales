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
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM filesSends WHERE id=? ");
        $statement->execute(array($id));
        $ficher=$statement->fetch();
        $name = $ficher['name'];
        $categorie =$ficher['id_subject'];
        $type=$ficher['type_cat'];
        $correction=$ficher['correction'];
        $link = $ficher['link'];
        $image="daFichier.png";

        if($univoco == "" || preg_match("([<>&()%'?+])", $univoco) || preg_match('/"/', $univoco))
        {
          header("Location: login.php?logout=si");
        }////////
        else
        {

          $statement = $db->prepare("INSERT INTO files (name,id_subject,type_cat,image,correction,link) values(?, ?, ?, ?, ?, ?)");
          $statement->execute(array($name,$categorie,$type,$image,$correction,$link));
          $statement = $db->prepare("DELETE FROM filesSends WHERE id = ?");
          $statement->execute(array($id));
            Database::disconnect();
        }

        header("Location: fichiers.php");


    function checkInput($data)
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
?>
