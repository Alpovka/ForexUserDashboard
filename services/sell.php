<?php
  include "config.php";
  
  if(isset($_POST["sellButton"])){
    //echo $_POST["sellButton"];

    $currency = $_POST["sellButton"];
    $select_query = "SELECT * FROM balance WHERE currency= '$currency'";
    $row = mysqli_fetch_assoc(mysqli_query($db,$select_query));
    
    $unit_value = (float)$row["unit_value"];
    $amount =  (float)$row["amount"];
    $total_value = $unit_value * $amount;
    //echo $total_value;

    //ADD TO HISTORY
    $pair = $currency."TRY";
    $insert_into_history_query = "INSERT INTO history(id, date, pair, side, price, amount, total) VALUES (DEFAULT, CURDATE() , '$pair', 'SELL' ,$unit_value, $amount, $total_value )";
    mysqli_query($db,$insert_into_history_query);


    $getTRYamount_query = "SELECT amount FROM balance WHERE currency = 'TRY'";

    $TRYamount = mysqli_fetch_assoc(mysqli_query($db,$getTRYamount_query))["amount"];


    if(isset($TRYamount)){
        $update__query = "UPDATE balance SET amount=($TRYamount + $total_value) WHERE currency = 'TRY'";   
        mysqli_query($db,$update__query); 

      
    }else {
        $create_query = "INSERT INTO `balance`(`id`, `currency`, `unit_value`, `amount`) VALUES (DEFAULT, 'TRY', 1, '$total_value')";
        mysqli_query($db,$create_query); 

    }
    $remove_query = "DELETE FROM balance WHERE currency = '$currency'";
    mysqli_query($db,$remove_query); 

    

}
header('Location: ' . $_SERVER["HTTP_REFERER"] );
exit;
  
?>