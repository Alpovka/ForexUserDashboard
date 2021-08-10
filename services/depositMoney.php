<?php
  include "config.php";
  

  
  if(isset($_GET["currency"])){

    echo "DONE";
    $currency = $_GET["currency"];
    $convertedVal = $_GET["convertedVal"];

    $amount = (float)$_GET["amount"];
    
    $unitValue = (float)($convertedVal/$amount);


    //ADD TO HISTORY
    $pair = $currency."TRY";
    $insert_into_history_query = "INSERT INTO history(id, date, pair, side, price, amount, total) VALUES (DEFAULT, CURDATE() , '$pair', 'DEPOSIT' ,$unitValue, $amount, $convertedVal )";
    mysqli_query($db,$insert_into_history_query);


    //echo $unitValue;
    $select_query= "SELECT * FROM balance WHERE currency =  '$currency'";

    $select_result =     mysqli_query($db,$select_query);
    if(mysqli_num_rows($select_result) == 1){
      //add to existing
      $row = mysqli_fetch_assoc($select_result);
      $additional_amount = $row["amount"];

      $new_amount = (float)$additional_amount+$amount;

      $update__query = "UPDATE balance SET amount=$new_amount WHERE currency = '$currency'";    
      mysqli_query($db,$update__query);

    }
    else {
      //create new one
      $depositMoney_query = "INSERT INTO balance(id, currency, unit_value,amount) VALUES (DEFAULT, '$currency','$unitValue', $amount)";
      mysqli_query($db,$depositMoney_query);

    }



  }
  header('Location: ' . $_SERVER["HTTP_REFERER"] );
  exit;
?>