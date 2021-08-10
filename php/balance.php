<?php
  include "services/config.php";

  $my_firstname = $_SESSION['firstname']   ;
  $my_lastname = $_SESSION['lastname']  ;
  $my_password = $_SESSION['password']  ;
  $my_id = $_SESSION['id']  ;



  $balance_total = 0;

  $get_balance_query = "SELECT * FROM balance";

  $result = mysqli_query($db,$get_balance_query);
  while($row = mysqli_fetch_assoc($result)){
    $unitV = $row["unit_value"];
    $amount = $row["amount"];
    $balance_total += $unitV*$amount;
  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Alperen Karavelioglu">
  <link rel="stylesheet" type="text/css" 
  href="balance.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/data.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/currency-flags/3.2.1/currency-flags.min.css">
    <title>User Dashboard</title>
</head>
<body>
  <!-- SIDE BAR -->
    <div class="sidenav">
      <div  class="ui container">
        <button id="sidenav-headers" class="ui active button">
          <a>FOREX APP</a>
          <a href="#" style="font-size: 0.8rem;">USER DASHBORAD</a>
        </button>
        <div style="margin: 0;" class="ui divider"></div>
        <img class="ui medium circular image" src="https://i.pinimg.com/564x/65/25/a0/6525a08f1df98a2e3a545fe2ace4be47.jpg">
        <p> <?php echo $my_firstname . " " . $my_lastname ?></p>
        <div style="margin: 0;" class="ui divider"></div>
      </div>

      <!-- NAVIGATION LINKS -->
      <div class="nav-links">
        <div id="nav-links" class="ui vertical labeled icon menu">
          <a id="market" href="index.php" class="item">
            <i class="money bill alternate outline icon"></i>
            Markets
          </a>
          <a href="balance.php" id="balance" class="item active">
            <i class="credit card outl icon"></i>
            Balance
          </a>
          <a href="history.php" id="history" class="item">
            <i class="history icon"></i>
            History
          </a>
          <div style="background-color: #fff;height: 48px;" class="ui fluid vertical animated button" tabindex="0">
            <div class="visible content">
              DETAILS
            </div>

            <!-- OPTIONS & EDIT PROFILE MODAL -->
            <div style="background-color: #fff;" class="hidden content">
              <button id="edit" class="ui tiny left attached button"><i class="edit icon"></i></button>
              <button id="options" class="right tiny attached ui button"><i class="cogs icon"></i></button>
            </div>
          </div>      
      </div>
      </div>
      </div>
      
      <!-- RIGHT SIDE OF THE PAGE |MAIN CONTENT| -->
      <div class="main">
          <!-- TOP BAR -->
          <div id="head" class="ui outline black raised segment">
            <div id="page-head" class="ui medium header">BALANCE</div>
            <div id="deposit" class="ui vertical animated button" tabindex="0">
              <div id="tot-money" class="visible content"><?php echo number_format($balance_total, 4);?> TRY</div>
              <div class="hidden content">
                DEPOSIT <i class="sign-in icon"></i>
              </div>
            </div>
          </div>

          
        <!-- CONTENT -->
        <div id="maindown">
          <!-- TABLE OF BALANCE -->
          <table id="balance-table" class="table compact hover">
            <thead>
              <tr><th>Currency</th>
                <th>Unit Value</th>
              <th>Amount</th>
              <th>Total Value</th>
              <th>Transactions</th>
            </tr>
          </thead>
          <tbody>
            <?php

              
              

              $result = mysqli_query($db,$get_balance_query);
              while($row = mysqli_fetch_assoc($result)){
                
                $id = $row["id"];
                $currency = $row["currency"];
                $unit_value = $row["unit_value"];
                $amount = $row["amount"];

                $buttonStatus = "";
                $buttonText = "";

                $style = "";
                if($currency == "TRY"){
                  $buttonStatus = "disabled";
                  $buttonText = "BASE CURRENCY";
                  $style = ' style=" font-size:1.2rem; font-weight:900; " ';
                }
                else{
                  $buttonStatus = "enabled";
                  $buttonText = "SELL";
                  $style = "";
                }
                
                
                
                echo '<tr>';
                echo  '<form action="services/sell.php" method="POST", id="sell-form">'.
                      '</form>';


                echo '<td ' . $style . '>' . $currency . '</td>';
                echo '<td ' . $style . '>' .  $unit_value .' </td>';
                echo '<td ' . $style . '>' .  $amount . '</td>';
                echo '<td ' . $style . '>' .  number_format($unit_value*$amount,4) . '</td>';
                echo '<td>';
                echo  '<button '. $buttonStatus . ' type="submit" form="sell-form" class="btn-grad" value = ' . $currency . ' name = "sellButton">' . $buttonText . '</button>';
                echo '</td>';
                echo '</tr>';

              
              }


            ?>

          </tbody>
        </table>
        <br>
    <!-- CHARTS FROM HIGHCHART -->
    <div id="chart-container">
      <figure class="highcharts-figure">
        <div id="container-pie"></div>
        <p style="visibility: hidden" class="highcharts-description">
          All color options in Highcharts can be defined as gradients or patterns.
          In this chart, a gradient fill is used for decorative effect in a pie
          chart.
        </p>
      </figure>
      <figure class="highcharts-figure">
        <div id="container"></div>
        <p style="visibility: hidden" class="highcharts-description">
          Highcharts has extensive support for time series, and will adapt
          intelligently to the input data. Click and drag in the chart to zoom in
          and inspect the data.
        </p>
        <table id="datatable">
          <thead>
            <tr>
              <th></th>
              
              <?php
                $get_currencies_query = "SELECT DISTINCT(currency) FROM balance";
                $result = mysqli_query($db,$get_currencies_query);

                while($row = mysqli_fetch_assoc($result)){
                  echo "<th>" . $row["currency"] . "</th>";
                }

              ?>
            </tr>
          </thead>
          <tbody>
            <th>AMOUNT</th>
            <?php
              $get_amounts_query = "SELECT amount FROM balance";
              $result = mysqli_query($db,$get_amounts_query);

              while($row = mysqli_fetch_assoc($result)){
                echo "<td>" . $row["amount"] . "</td>";
              }
            ?>
            
            <tr>
              <th>UNIT VALUE</th>
              <?php
                $get_unit_values_query = "SELECT unit_value FROM balance";
                $result = mysqli_query($db,$get_unit_values_query);

                while($row = mysqli_fetch_assoc($result)){
                  echo "<td>" . $row["unit_value"] . "</td>";
                }
              ?>
            </tr>
            <tr>
            <th>TOTAL VALUE</th>
              <?php
                $get_values_query = "SELECT * FROM balance";
                $result = mysqli_query($db,$get_values_query);

                while($row = mysqli_fetch_assoc($result)){
                  echo "<td>" . number_format($row["unit_value"] * $row["amount"] , 4) . "</td>";
                }
              ?>

              
            </tr>
          </tbody>
        </table>
      </figure>
    </div>
  </div>
</div>
       <!--********************** END OF DEFAULT VISIBLE CONTENT ****************************-->


        <!-- LOADER -->
          <div class="loader-wrapper">
            <div class="loader">
              <div class="bar1"></div>
              <div class="bar2"></div>
              <div class="bar3"></div>
              <div class="bar4"></div>
              <div class="bar5"></div>
              <div class="bar6"></div>
            </div>
          </div>

          <!---------------------------------- MODALS -------------------------------------------->

          <!-- DEPOSIT BUTTON MODAL -->
          <div id="deposit-modal" class="ui basic modal">
            <div class="ui icon header"><p style="display: none;" id="hidden-deposit"></p><p id="warnx"></p>
              <div style="display: flex;justify-content: center;">
                <div style="margin-right: 10px;">
                  <i class="cc mastercard icon"></i>
                  <i class="cc paypal icon"></i>
                </div>
                <div style="margin-right: 10px;">
                  <i class="cc visa icon"></i>
                <i class="cc stripe icon"></i>
                </div>
              </div>
              <div class="ui right labeled input">
                <label for="amount" class="ui label"><div class="ui compact menu">
                  <div id="deposit-selection-dropdown" class="ui search selection dropdown">
                    <input type="hidden" name="currency">
                    <i class="dropdown icon"></i>
                    <div style="display: flex;justify-content: space-evenly;align-items: baseline;font-size: 0.5;" class="default text">Select Currency</div>
                    <div id="select-currency-menu" class="menu">
                      <div class="item">EURO (EUR) <i class=" tiny euro sign icon"></i></div>
                      <div class="item">DOLLAR (USD) <i class=" tiny dollar sign icon"></i></div>
                      <div class="item">POUND (GBP) <i class=" tiny pound sign icon"></i></div>
                      <div class="item">LIRA (TRY) <i class=" tiny lira sign icon"></i></div>
                      <div class="item">YEN (JPY) <i class=" tiny yen sign icon"></i></div>
                    </div>
                   </div>
                </div></label>
                <input type="number" placeholder="Amount" id="amount">
                <div id="converted-currency" style="display: flex;justify-content: center;align-items: center;" class="ui basic label"></div>
              </div>
            </div>
            <div class="actions">
              <div style="float: left;" class="ui red cancel button">
                <i class="remove icon"></i>
                CANCEL
              </div>
              <div id="confirm-btn" class="ui disabled green ok button">
                <i class="checkmark icon"></i>
                CONFIRM
              </div>
            </div>
          </div>

          <!-- OPTIONS MENU BUTTON MODAL -->
          <div id="loader-modal" class="ui basic modal">
            <div id="loader" class="ui active centered inline loader"></div>
          </div>

          <!-- EDIT PROFILE BUTTON MODAL  -->
          <div id="edit-modal" class="ui modal">
            <i class="close icon"></i>
            <div style="background-color: #f4f4f4;" class="header">
              Edit Profile
            </div>
            <div class="image content">
              <div class="ui medium image">
                <img src="https://i.pinimg.com/564x/65/25/a0/6525a08f1df98a2e3a545fe2ace4be47.jpg">
              </div>
              <div style="margin-top: 15px;text-align: center;font-size: 1.2rem;" class="description">
                <form class="ui form"  id="edit_form" action="services/editProfileService.php" method="POST">
                <h4 class="ui dividing header">Profile Information</h4>
                <div class="field">
                  <label>Name</label>
                  <div class="two fields">
                    <div class="field">
                      <input type="text" name="firstname" placeholder= <?php echo $my_firstname?>>
                    </div>
                    <div class="field">
                      <input type="text" name="lastname" placeholder=<?php echo $my_lastname?>>
                    </div>
                  </div>
                    <label>Password</label>
                    <div class="field">
                      <input id="password" type="password" name="password" placeholder="Password">
                      <div class="ui divider"></div>
                      <input id="password-repeat" type="password" name="password-repeat" placeholder="Password (Again)">
                  </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="actions">
                <button type="submit" form="edit_form" style="background-color: green;color: #fff;border: 1px solid green;border-radius: 3px;cursor: pointer;padding: 10px 15px">SAVE <i class="checkmark icon"></i></button>
            </div>
          </div>
</body>
  <script>
    $(window).on("load", () => {
      $(".loader-wrapper").fadeOut("slow");
    })

    /***** Password edit validation *****/
    $("button[form=edit_form]").on("click", (e) => {
        var password = $("#password")[0].value;
        var passwordRepeat = $("#password-repeat")[0].value;
        if(password != passwordRepeat){
          e.preventDefault();
          alert("Password doesn't match!");
        }
    })

    $(document).ready(function(){
      $('#balance-table').DataTable(); // DataTable Initialization
      $(".ui.dropdown").dropdown(); // Dropdown Button Initialization
      $('.ui.modal').modal(); // Modal Initialization

      // MODAL TRIGGERS
      $("#edit").on("click", () => {
        $('#edit-modal').modal('show');
      });

      $("#options").on("click", () => {
        $('#loader-modal').modal('show');
        $("#loader").show();
      });

      $("#deposit").on("click", () => {
        $('#deposit-modal').modal('show');
      });
      
      $(".ui.green.ok.button").on("click", () => { // Sending proper data to database
        var currencySIGN = $("#hidden-deposit")[0].innerText;
        var amount = $("#amount")[0].value;

        var convertedValue = $("#converted-currency")[0].innerText;
        console.log(depositModalRight);

        console.log("currency:" + currencySIGN);
        window.location.href = 'services/depositMoney.php?currency=' + currencySIGN + "&convertedVal=" + convertedValue +  "&amount=" + amount; 

      });

    });
  </script>
  <script src="balance.js"></script>
</html>