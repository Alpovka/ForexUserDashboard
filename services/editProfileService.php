<?php
  include "config.php";

  $my_firstname = $_SESSION['firstname']   ;
  $my_lastname = $_SESSION['lastname']  ;
  $my_password = $_SESSION['password']  ;
  $my_id = $_SESSION['id']  ;


    
  if(isset($_POST["firstname"])){
    if($_POST["firstname"]!=""){
        $new_name = $_POST["firstname"];

        $update_query = "UPDATE user SET firstname = '$new_name' WHERE id = " . $my_id;
        mysqli_query($db,$update_query);
        $_SESSION["firstname"] = $new_name;

    }
    else {
        echo "NO";
    }
    
  }
  if(isset($_POST["lastname"])){
    if($_POST["lastname"]!=""){
        $new_name = $_POST["lastname"];
        
        $update_query = "UPDATE user SET lastname = '$new_name' WHERE id = " . $my_id;
        mysqli_query($db,$update_query);
        $_SESSION["lastname"] = $new_name;;
        
    }
    else {
        echo "NO";
    }
    
  }
  if(isset($_POST["password"]) && isset($_POST["password-repeat"])){
    if($_POST["password"]!="" && $_POST["password-repeat"]!=""){
        $new_password = $_POST["password"];
        $update_query = "UPDATE user SET password = '$new_password' WHERE id = " . $my_id;

        mysqli_query($db,$update_query);
        $_SESSION["password"] = $new_password;
        
    }
    else {
        echo "NO";
    }

    header('Location: ' . $_SERVER["HTTP_REFERER"] );
    //header("Location: ../index.php",true);
    //header("location:javascript://history.go(-1)");
    exit;
  }
?>

