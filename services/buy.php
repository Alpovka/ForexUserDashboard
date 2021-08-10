<?php
  include "config.php";
  
    if(isset($_GET["currency"])){
        echo $_GET["currency"] . " " . $_GET["unit_value"]. " " .$_GET["incoming_amount"] . " ".  $_GET["outgoing_amount"];
        
        $currency = $_GET["currency"];
        $unit_value = (float)$_GET["unit_value"];
        $incoming_amount = (float)$_GET["incoming_amount"];
        $outgoing_amount = (float)$_GET["outgoing_amount"];

        //case1: target doesnt exist, then add it, substract from TRY
        //case2: target doesnt exist, then add it, substract from TRY, if its 0, remove TRY
        //case3: target exists, then add it, substract from TRY
        //case4: target exists, then add it, substract from TRY, if its 0, remove TRY
        //we never consider the case when we dont have TRY row in the table or less than outgoing amount,
        //since we already check it before purchase operation
        
        //ADD TO HISTORY
        $pair = $currency."TRY";
        $insert_into_history_query = "INSERT INTO history(id, date, pair, side, price, amount, total) VALUES (DEFAULT, CURDATE() , '$pair', 'BUY' ,$unit_value, $incoming_amount, $outgoing_amount )";
        mysqli_query($db,$insert_into_history_query);



        $checkTarget_query = "SELECT * FROM balance WHERE currency= '$currency'";
        $rowTarget = mysqli_fetch_assoc(mysqli_query($db,$checkTarget_query));

        if(!isset($rowTarget)){

            $insertTarget_query = "INSERT INTO balance(id, currency, unit_value,amount) VALUES (DEFAULT, '$currency','$unit_value', $incoming_amount)";

            $checkTRY_query = "SELECT * FROM balance WHERE currency='TRY'";
            $rowTRY = mysqli_fetch_assoc(mysqli_query($db,$checkTRY_query));

            if($rowTRY["amount"] > $outgoing_amount){
                //case1
                //insert target
                //substract from TRY
                $new_TRY_amount = $rowTRY["amount"] - $outgoing_amount;
                $update_TRY_amount_query = "UPDATE balance SET amount='$new_TRY_amount' WHERE currency = 'TRY'";
                mysqli_query($db,$update_TRY_amount_query);
            }
            else{
                //case2
                //remove TRY since becomes 0
                $remove_TRY_query = "DELETE FROM balance WHERE currency='TRY'";
                mysqli_query($db,$remove_TRY_query);
            }
            mysqli_query($db,$insertTarget_query);

        }
        else{

            $target_amount = $rowTarget["amount"];
            $new_target_amount = $target_amount + $incoming_amount;

            $checkTRY_query = "SELECT * FROM balance WHERE currency='TRY'";
            $rowTRY = mysqli_fetch_assoc(mysqli_query($db,$checkTRY_query));

            $update_target_amount_query = "UPDATE balance SET amount= '$new_target_amount' WHERE currency= '$currency'";


            if($rowTRY["amount"] > $outgoing_amount){
                //case1
                //insert target
                //substract from TRY
                $new_TRY_amount = $rowTRY["amount"] - $outgoing_amount;
                $update_TRY_amount_query = "UPDATE balance SET amount='$new_TRY_amount' WHERE currency = 'TRY'";
                mysqli_query($db,$update_TRY_amount_query);
            }
            else{
                //case2
                //remove TRY since becomes 0
                $remove_TRY_query = "DELETE FROM balance WHERE currency='TRY'";
                mysqli_query($db,$remove_TRY_query);
            }

            mysqli_query($db,$update_target_amount_query);

        }




       
    }
    header('Location: ' . $_SERVER["HTTP_REFERER"] );
    exit;
    
?>