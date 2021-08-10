<?php
  include "config.php";
  $my_firstname = $_SESSION['firstname']   ;
  $my_lastname = $_SESSION['lastname']  ;
  $my_password = $_SESSION['password']  ;
  $my_id = $_SESSION['id']  ;

    echo "HEY";
    
      
  if(isset($_POST["DeleteButton"])){
    echo "asd";
    $delete_query = "DELETE FROM history WHERE id=" . $_POST["DeleteButton"];
    mysqli_query($db,$delete_query);
    
  }

  header('Location: ' . $_SERVER["HTTP_REFERER"] );
  exit;
?>